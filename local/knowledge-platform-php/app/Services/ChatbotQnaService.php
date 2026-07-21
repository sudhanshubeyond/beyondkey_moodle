<?php

namespace App\Services;

use App\Models\CompanyChatbot;
use App\Models\QnaSummary;
use App\Models\QueryDocumentDetails;
use App\Models\QueryResponse;
use App\Services\Exceptions\AzureRateLimitException;
use App\Support\PgVector;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Port of BAL/Classes/ChatbotQNA.cs — the RAG orchestrator.
 *
 * Hardcoded values from the .NET source are preserved verbatim (this is a
 * faithful 1:1 port, not a redesign): ChatBotId = 1, multi-turn on / window 5,
 * fusion weights 0.65/0.35, distance cutoff 0.72, recall LIMIT 30, final top-3,
 * greeting similarity threshold 0.82.
 */
class ChatbotQnaService
{
    /**
     * Greeting / small-talk patterns used by isTrivialTurn (1:1 with the .NET
     * GreetingPatterns array). NOTE: matching is case-sensitive (like C#
     * string.Contains) against a lower-cased question — see isTrivialTurn.
     *
     * @var array<int,string>
     */
    private const GREETING_PATTERNS = [
        'Hi', 'Hii', 'Hiii', 'Hi!', 'Hi!!', 'Hi there', 'Hi buddy', 'Hi bot', 'Hi AI', 'Hi assistant', 'Hi chatbot',
        'Hello', 'Hello!', 'Hello!!', 'Hello there', 'Hello buddy', 'Hello bot', 'Hello AI', 'Hello assistant', 'Hello chatbot',
        'Hey', 'Hey!', 'Hey!!', 'Hey there', 'Hey buddy', 'Hey bot', 'Hey AI', 'Hey assistant', 'Hey chatbot',
        'Good morning', 'Good afternoon', 'Good evening', 'Good day',
        'Greetings', 'Hola', 'Yo', 'Hlo', 'Hy', 'Hye',
        'Nice to meet you', 'Nice meeting you', 'Pleasure to meet you',
        'Long time no see', 'Good to see you', 'Good to talk to you',
    ];

    public function __construct(
        private AzureOpenAiService $azure,
    ) {
    }

    /**
     * Port of IsTrivialTurn — a turn whose Q&A carries no durable topical
     * memory (greeting/small-talk or too short) should not be summarized.
     *
     * Faithful to the .NET version: the greeting check uses case-sensitive
     * str_contains (like C# string.Contains) against a lower-cased question,
     * so the GREETING_PATTERNS (which are capitalized) effectively never match
     * on their own — the short-length checks are what fire in practice.
     */
    private function isTrivialTurn(string $question, string $answer): bool
    {
        $q = rtrim(strtolower(trim($question)), '!.?');

        // Very short question matching a greeting/small-talk pattern.
        if (count(explode(' ', $q)) <= 3) {
            foreach (self::GREETING_PATTERNS as $g) {
                if (str_contains($q, $g)) {
                    return true;
                }
            }
        }

        // Question or answer too short to carry real topical content.
        if (mb_strlen(trim($question)) < 6 || mb_strlen(trim($answer)) < 15) {
            return true;
        }

        return false;
    }

    // ── DeleteQNASessionHistory (ChatbotQNA.cs:33) ─────────────────────────
    public function deleteQnaSessionHistory(?string $sessionId): array
    {
        if ($sessionId === null || trim($sessionId) === '') {
            return ['Status' => 'false', 'Message' => 'Failed'];
        }

        $rows = QueryResponse::where('sessionid', $sessionId)->get();

        if ($rows->isEmpty()) {
            return ['Status' => 'false', 'Message' => 'Failed'];
        }

        QueryResponse::where('sessionid', $sessionId)->delete();

        return ['Status' => 'true', 'Message' => 'Success'];
    }

    // ── GetSingleQuestionFromComposite (ChatbotQNA.cs:77) ──────────────────
    /**
     * @return array<int,string>
     */
    public function getSingleQuestionFromComposite(int $chatbotId, string $compositeQuery): array
    {
        $singleQuestionsList = [];
        $assistantResponse = '';

        $chatbotPrompt = (string) config('prompts.composite_questions');

        $assistantResponse = trim($this->azure->chat(
            [
                ['role' => 'system', 'content' => $chatbotPrompt],
                ['role' => 'user', 'content' => $compositeQuery],
            ],
            ['temperature' => 0.5, 'top_p' => 0.9]
        ));

        if ($assistantResponse !== '') {
            $parsed = json_decode($assistantResponse, true);
            if (is_array($parsed) && count($parsed) > 0) {
                // Keep only non-blank string entries (mirrors deserialize to List<string>).
                // The model occasionally emits an empty element (e.g. [""]); embedding a
                // blank string throws, so drop blanks here and fall back below if none remain.
                $parsed = array_values(array_filter(
                    $parsed,
                    static fn ($q) => is_string($q) && trim($q) !== ''
                ));
                if (count($parsed) > 0) {
                    $singleQuestionsList = $parsed;
                }
            }
        }

        // Never return empty — fall back to the original composite query.
        if (count($singleQuestionsList) === 0) {
            $singleQuestionsList[] = $compositeQuery;
        }

        return $singleQuestionsList;
    }

    // ── GetSimiliarChunks (ChatbotQNA.cs:155) — the re-ranking core ────────
    /**
     * @param  array<int,array<int,float>>  $embeddedQueryList  one embedding per sub-question
     * @return array<int,array<int,array<string,mixed>>>  list (per sub-question) of chunk lists
     */
    public function getSimilarChunks(string $userQuery, array $embeddedQueryList, int $chatbotId): array
    {
        $result = [];

        foreach ($embeddedQueryList as $embeddedQuery) {
            $documentData = [];

            if ($this->checkGreetingQuestion($embeddedQuery)) {
                $result[] = $documentData; // empty for greetings
                continue;
            }

            $vecLiteral = PgVector::toLiteral($embeddedQuery);

            // Vector recall: top-30 candidates by cosine distance for this chatbot's docs.
            $vectorCandidates = DB::select(
                'SELECT
                    dc.documentchunk_id   AS documentchunk_id,
                    dc.document_id        AS documentid,
                    d.documentname        AS documentname,
                    d.documenturl         AS documenturl,
                    d.documenttype        AS documenttype,
                    dc.pagenumber         AS pageno,
                    dc.textdata           AS textdata,
                    (dc.embeddedchunkdata <=> ?::vector) AS similarity
                 FROM documentchunkdetails dc
                 JOIN documentdetails d ON dc.document_id = d.document_id
                 WHERE d.companychatbot_id = ?
                 ORDER BY similarity ASC
                 LIMIT 30',
                [$vecLiteral, $chatbotId]
            );

            // Distance cutoff (hardcoded 0.72 in the .NET source; equal to SimilarityThreshold).
            $vectorCandidates = array_values(array_filter(
                $vectorCandidates,
                static fn ($c) => (float) $c->similarity <= 0.72
            ));

            $chunkIds = array_map(static fn ($c) => (int) $c->documentchunk_id, $vectorCandidates);

            // BM25-style keyword scores for the surviving candidates. Note: the .NET code
            // scores against the full (rewritten) userQuery for every sub-question, not the
            // per-sub-question text — preserved here.
            $bm25Dict = [];
            if (count($chunkIds) > 0) {
                $placeholders = implode(',', array_fill(0, count($chunkIds), '?'));
                $bm25Rows = DB::select(
                    "SELECT
                        dc.documentchunk_id AS documentchunk_id,
                        ts_rank_cd(dc.tsv, plainto_tsquery('english', ?)) AS keywordscore
                     FROM documentchunkdetails dc
                     WHERE dc.documentchunk_id IN ({$placeholders})",
                    array_merge([$userQuery], $chunkIds)
                );
                foreach ($bm25Rows as $row) {
                    $bm25Dict[(int) $row->documentchunk_id] = (float) $row->keywordscore;
                }
            }

            // Score fusion: 0.65 * (1 - distance) + 0.35 * bm25.
            $scored = [];
            foreach ($vectorCandidates as $c) {
                $id = (int) $c->documentchunk_id;
                $similarity = (float) $c->similarity;
                $keywordScore = $bm25Dict[$id] ?? 0.0;
                $vectorScore = 1 - $similarity;
                $combined = (0.65 * $vectorScore) + (0.35 * $keywordScore);

                $scored[] = [
                    'documentid' => (int) $c->documentid,
                    'documentname' => $c->documentname,
                    'documenturl' => $c->documenturl,
                    'documenttype' => $c->documenttype,
                    'pageno' => $c->pageno === null ? null : (int) $c->pageno,
                    'textdata' => $c->textdata,
                    'similarity' => $similarity,
                    'keywordsimilarity' => $keywordScore,
                    'combinedsimilarity' => $combined,
                ];
            }

            // Re-rank by combined score, keep top 3.
            usort($scored, static fn ($a, $b) => $b['combinedsimilarity'] <=> $a['combinedsimilarity']);
            $documentData = array_slice($scored, 0, 3);

            $result[] = $documentData;
        }

        return $result;
    }

    // ── CheckGreetingQuestion (ChatbotQNA.cs:254) ──────────────────────────
    /**
     * @param  array<int,float>  $vectorData
     */
    public function checkGreetingQuestion(array $vectorData): bool
    {
        $similarityThreshold = 0.82;
        $distanceThreshold = 1 - $similarityThreshold; // 0.18

        $vecLiteral = PgVector::toLiteral($vectorData);

        $rows = DB::select(
            'SELECT greetingsquestionid
             FROM greetingsquestion
             WHERE (embeddeddata <=> ?::vector) <= ?
             LIMIT 3',
            [$vecLiteral, $distanceThreshold]
        );

        return count($rows) > 0;
    }

    // ── SaveAndUpdateHistory (ChatbotQNA.cs:274) ───────────────────────────
    public function saveAndUpdateHistory(string $sessionId, string $question, string $answer): void
    {
        $summaryContext = $this->isTrivialTurn($question, $answer)
            ? ''
            : $this->generateQnaSummaryContext($question, $answer);

        $maxQNo = (int) (QnaSummary::where('sessionid', $sessionId)->max('questionno') ?? 0);

        QnaSummary::create([
            'sessionid' => $sessionId,
            'type' => 'QNA',
            'questionno' => $maxQNo + 1,
            'question' => $question,
            'answer' => $answer,
            'summarycontext' => $summaryContext,
            'createdat' => now('UTC'),
        ]);
    }

    // ── GetdocumentMergeContent (ChatbotQNA.cs:296) ────────────────────────
    /**
     * @param  array<int,array<string,mixed>>  $chunks
     */
    public function getDocumentMergeContent(array $chunks, string $concatenatedText): string
    {
        if (count($chunks) > 0) {
            $sequential = array_filter($chunks, static fn ($c) => trim((string) ($c['textdata'] ?? '')) !== '');
            usort($sequential, static fn ($a, $b) => ($a['pageno'] ?? 0) <=> ($b['pageno'] ?? 0));

            foreach ($sequential as $chunk) {
                $concatenatedText .= $chunk['textdata']."\n";
            }
        }

        return $concatenatedText;
    }

    // ── GetAnswerSummary (ChatbotQNA.cs:321) ───────────────────────────────
    /**
     * @return array<string,mixed>|null  {intent, topic, key_facts}
     */
    public function getAnswerSummary(string $originalAnswer): ?array
    {
        $summaryPrompt = <<<TXT

                You are a memory extraction engine for a Retrieval-Augmented Generation (RAG) AI system.
                Your task is to extract only the essential conversational memory that will be useful
                for future turns in the same conversation.
                STRICT RULES:
                - Capture meaning, not wording
                - Keep memory minimal, durable, and reusable
                - Do NOT copy explanations, examples, or long text
                - Do NOT restate obvious or generic information
                - Omit anything that does not help future understanding
                - Do NOT infer or add new information
                Extract the following items when the answer contains any meaningful information:
                - intent: the user’s goal or purpose, explicitly stated or clearly implied by the answer content (MANDATORY)
                - topic: the main subject discussed (MANDATORY)
                - key_facts: confirmed factual statements or conclusions explicitly stated (MANDATORY)
                ADDITIONAL RULES:
                - If the answer contains any explanatory or factual content, extract at least one key fact.
                - If the answer is a refusal, clarification, or out-of-scope response, still infer intent and topic from the question or response.
                - Do NOT return an empty JSON object.
                OUTPUT FORMAT:
                Always return the summary strictly in the following JSON structure.
                All fields are REQUIRED.
                {
                  "intent": "",
                  "topic": "",
                  "key_facts": []
                }
                OUTPUT REQUIREMENTS:
                - Output valid JSON only
                - No commentary, no markdown, no extra text
                - Do NOT include examples
                - Do NOT output anything except the JSON object
                - Do not include backticks or labels like 'json'
                Answer to summarize:
                {$originalAnswer}
                TXT;

        $json = trim($this->azure->complete($summaryPrompt));
        $json = trim(str_ireplace(['```json', '```'], '', $json));

        if ($json === '' || $json === '{}') {
            return null;
        }

        $memory = json_decode($json, true);

        return is_array($memory) ? $memory : null;
    }

    // ── GetLLMResponse (ChatbotQNA.cs:388) ─────────────────────────────────
    /**
     * @return array<string,mixed>  {LLMAnswer, AnswerFlag, LLMAnswerSummary}
     */
    public function getLlmResponse(
        int $chatbotId,
        string $userQuery,
        string $documentContent,
        int $documentContentResultCount,
        string $chatbotPrompt,
        string $genericPrompt,
        string $chatbotRole,
        string $sessionId,
        bool $isMultiTurnEnabled,
        int $multiTurnCount
    ): array {
        $output = ['LLMAnswer' => null, 'AnswerFlag' => 0, 'LLMAnswerSummary' => null];

        $maxRetries = (int) config('knowledge.max_retries');
        $initialDelay = (int) config('knowledge.delay_in_seconds');

        // Build the multi-turn conversation context block up front.
        $conversationContextBlock = '';
        if ($isMultiTurnEnabled && $multiTurnCount > 0) {
            $recentSummaries = $this->getLastNSummaryContexts($sessionId, $multiTurnCount);
            if (count($recentSummaries) > 0) {
                $sb = "=== PREVIOUS CONVERSATION CONTEXT ===\n";
                foreach ($recentSummaries as $i => $s) {
                    $sb .= ($i + 1).'. Question: '.$s->question."\n";
                    $sb .= '   '.$s->summarycontext."\n";
                }
                $sb .= "=====================================\n";
                $conversationContextBlock = $sb;
            }
        }

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $content = "Content : {$documentContent}";
                $userQueryLine = "User question : {$userQuery}";
                $todayDate = 'Today\'s date is '.now()->format('F j, Y').'.';
                $finalPrompt = "{$todayDate}\n\n{$chatbotPrompt}";

                if ($documentContentResultCount > 0) {
                    $chatbot = CompanyChatbot::where('companychatbot_id', $chatbotId)->first();

                    if ($chatbot !== null) {
                        $completePrompt = "{$conversationContextBlock}\n{$userQueryLine}\n{$content}";

                        $raw = $this->azure->chat([
                            ['role' => 'system', 'content' => $finalPrompt],
                            ['role' => 'user', 'content' => $completePrompt],
                        ]);

                        $json = trim(str_ireplace(['```json', '```'], '', $raw));

                        $llm = $json !== '' ? json_decode($json, true) : null;

                        // Mirrors the .NET code, which dereferences llmQnaResponse directly
                        // (a null/invalid payload would throw — an unhandled non-429 error).
                        if (! is_array($llm) || ! array_key_exists('LLMAnswer', $llm)) {
                            throw new RuntimeException('LLM response was not valid JSON with an LLMAnswer field.');
                        }

                        $output['LLMAnswer'] = $llm['LLMAnswer'] ?? null;
                        $output['AnswerFlag'] = (int) ($llm['Flag'] ?? 0);

                        if ($output['AnswerFlag'] > 0) {
                            $output['LLMAnswerSummary'] = $this->getAnswerSummary((string) $output['LLMAnswer']);
                        }
                    }
                } else {
                    $completePrompt = "{$conversationContextBlock}\nUserQuery : {$userQueryLine}";

                    $raw = $this->azure->chat([
                        ['role' => 'system', 'content' => $genericPrompt],
                        ['role' => 'user', 'content' => $completePrompt],
                    ]);

                    $output['LLMAnswer'] = $raw;
                    $output['LLMAnswerSummary'] = ['intent' => null, 'topic' => null, 'key_facts' => null];
                }

                // Save multi-turn history right after a successful answer.
                if (! empty($output['LLMAnswer']) && $isMultiTurnEnabled) {
                    $this->saveAndUpdateHistory($sessionId, $userQuery, (string) $output['LLMAnswer']);
                }

                return $output;
            } catch (AzureRateLimitException $ex) {
                if ($attempt >= $maxRetries) {
                    break;
                }
                $backoffSeconds = $initialDelay * (2 ** ($attempt - 1));
                $jitterMs = random_int(500, 1500);
                usleep(($backoffSeconds * 1000 + $jitterMs) * 1000);
            }
        }

        throw new RuntimeException('Failed to generate LLM response after maximum retries due to rate limiting.');
    }

    // ── GetQNAResponse (ChatbotQNA.cs:530) ─────────────────────────────────
    /**
     * @param  array<string,mixed>  $qnaResponseOutput
     * @param  array<int,array<string,mixed>>  $documentChunks
     * @return array<string,mixed>  QueryResp
     */
    public function getQnaResponse(string $query, array $qnaResponseOutput, array $documentChunks, string $sessionId): array
    {
        $queryResp = ['QueryDocumentDetails' => [], 'Answer' => null];
        $documentDetailsList = [];
        $utcNow = now('UTC');

        DB::beginTransaction();
        try {
            $queryResponse = QueryResponse::create([
                'question' => $query,
                'answer' => $qnaResponseOutput['LLMAnswer'] ?? null,
                'sessionid' => $sessionId,
                'createdat' => $utcNow,
            ]);

            if (count($documentChunks) > 0) {
                // Rank/dedupe documents by URL, keeping the best (lowest) similarity per URL.
                $groups = [];
                foreach ($documentChunks as $c) {
                    if ($c === null || ($c['pageno'] ?? null) === null) {
                        continue;
                    }
                    $url = $c['documenturl'];
                    if (! isset($groups[$url])) {
                        $groups[$url] = [
                            'DocumentName' => $c['documentname'],
                            'DocumentUrl' => $url,
                            'best' => $c,
                        ];
                    } elseif (($c['similarity'] ?? PHP_FLOAT_MAX) < ($groups[$url]['best']['similarity'] ?? PHP_FLOAT_MAX)) {
                        $groups[$url]['best'] = $c;
                    }
                }

                $ranked = array_map(static function ($g) {
                    return [
                        'DocumentName' => $g['DocumentName'],
                        'DocumentUrl' => $g['DocumentUrl'],
                        'PageNo' => $g['best']['pageno'],
                        'BestSimilarity' => $g['best']['similarity'],
                    ];
                }, array_values($groups));

                usort($ranked, static fn ($a, $b) => $a['BestSimilarity'] <=> $b['BestSimilarity']);

                foreach ($ranked as $doc) {
                    $documentDetailsList[] = [
                        'queryresponseid' => $queryResponse->queryresponseid,
                        'documentname' => $doc['DocumentName'],
                        'documenturl' => $doc['DocumentUrl'],
                        'pageno' => $doc['PageNo'],
                        'createdat' => $utcNow,
                    ];
                }

                if (count($documentDetailsList) > 0) {
                    QueryDocumentDetails::insert($documentDetailsList);
                }
            }

            DB::commit();

            $queryResp['Answer'] = $queryResponse->answer;
            $queryResp['QueryDocumentDetails'] = array_map(static fn ($d) => [
                'PageNumber' => $d['pageno'],
                'DocumentName' => $d['documentname'],
                'DocumentURL' => $d['documenturl'],
            ], $documentDetailsList);
        } catch (\Throwable $ex) {
            DB::rollBack();
            throw $ex;
        }

        return $queryResp;
    }

    // ── GetNormalQuestionPrompt (ChatbotQNA.cs:623) ────────────────────────
    public function getNormalQuestionPrompt(string $chatbotRole): string
    {
        return <<<TXT

            You are an intelligent, context-aware assistant operating under a clearly defined role.

            ROLE:
            {$chatbotRole}

            CORE RULES (ALWAYS FOLLOW):
            - Answer only the specific question asked.
            - Use only the provided content.
            - Do NOT add external knowledge.
            - Do NOT hallucinate, assume, or infer missing information.
            - Maintain a professional, clear, and structured tone.

            CLARITY & PRESENTATION RULES (STRICT):
            - Structure answers using clear section labels written as plain text.
            - Prefer bullet points using '-' for listing information.
            - Keep explanations concise and logically ordered.
            - Avoid decorative formatting symbols such as *, **, _, markdown, or code formatting.
            - Do NOT include any text outside the JSON object.

            GREETING HANDLING RULE (CRITICAL):
            - IMPORTANT:
              If a greeting is followed by a clear informational or document-based question
              in the same message, IGNORE the greeting completely and process the message
              as a normal question.

            DECISION LOGIC (MANDATORY):
            1. If the question can be answered using the provided document content:
               - Generate a clear, accurate, and exhaustive answer grounded strictly in the content.
               - Structure the answer using bold headings and bullet points.
               - Append a short, professional line encouraging the user to refer to the listed documents for further understanding. but ONLY inside the "LLMAnswer" field. Do NOT write anything outside the JSON object.
               - Set Flag = 1.

            2. If the question is outside your role or cannot be answered using the provided content:
               - You MUST populate "LLMAnswer" with a clear, polite refusal message.
               - The refusal message MUST:
                 - State that the information is not available in the provided content.
                 - Reference the subject directly from the user’s question.
                 - Guide the user to ask something related to your role by briefly referencing
                   the key subject areas or domains you are responsible for.
               - Do NOT mention documents or suggest further reading.
               - Set Flag = 0.

            IMPORTANT OUT-OF-SCOPE HANDLING:
            - Even if the model generally knows the answer, it MUST be treated as out of scope
              if it is not supported by the provided content.
            - Never answer general knowledge questions outside the document context.

            OUTPUT REQUIREMENTS:
            - Return ONLY a valid JSON object.

            {
                 "LLMAnswer": "REQUIRED_STRING",
                 "Flag": 0
            }

            - Do NOT output anything except the JSON object. Do not include any explanations or extra text like 'json' OR any backticks — only return the final JSON object.
            TXT;
    }

    // ── GetLastNSummaryContexts (ChatbotQNA.cs:697) ────────────────────────
    /**
     * @return array<int,\App\Models\QnaSummary>
     */
    public function getLastNSummaryContexts(string $sessionId, int $count): array
    {
        return QnaSummary::where('sessionid', $sessionId)
            ->where('type', 'QNA')
            ->whereNotNull('summarycontext')
            ->where('summarycontext', '!=', '')
            ->orderByDesc('questionno')
            ->limit($count)
            ->get()
            ->sortBy('questionno')
            ->values()
            ->all();
    }

    // ── RewriteQuestionFromSummaries (ChatbotQNA.cs:709) ───────────────────
    /**
     * @param  array<int,\App\Models\QnaSummary>  $recentSummaries
     */
    public function rewriteQuestionFromSummaries(string $originalQuestion, array $recentSummaries): string
    {
        $context = '';
        foreach ($recentSummaries as $i => $s) {
            $context .= ($i + 1).'. '.$s->summarycontext."\n";
        }

        $prompt = <<<TXT

            Given this previous conversation context:
            {$context}

            Rewrite this question to be fully self-contained and clear
            without needing the conversation context:
            '{$originalQuestion}'

            Rules:
            - Replace pronouns like it, its, they, that, those, the first one,
              the previous one with the actual subject from the context above
            - Keep the rewritten question short and natural
            - If the question is already self-contained and clear, return it as-is
            - Return ONLY the rewritten question, nothing else
            TXT;

        $rewritten = trim($this->azure->chat(
            [
                ['role' => 'system', 'content' => 'You are a question rewriter. Return only the rewritten question.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            ['temperature' => 0]
        ));

        return $rewritten === '' ? $originalQuestion : $rewritten;
    }

    // ── GenerateQnASummaryContext (ChatbotQNA.cs:762) ──────────────────────
    public function generateQnaSummaryContext(string $question, string $answer): string
    {
        $prompt = <<<TXT
            Given this previous conversation context:
            {$question}
            Question to evaluate:
            '{$answer}'
    
            Step 1: Decide if this question is CONTEXT-DEPENDENT — meaning either:
            (a) it contains an explicit anaphor (it, its, they, that, those, this,
                the first one, the previous one), OR
            (b) it references an action, benefit, or process (e.g. adding family
                members, claim payment, premium payment, coverage, cost) using a
                GENERIC/bare reference (e.g. 'the plan', 'benefits?') rather than a
                specific proper name, such that the correct answer depends on
                knowing WHICH plan is being discussed.
    
            A question is NOT context-dependent if:
            - it is fully general/plural in nature (e.g. 'what plans are there'),
            - it asks for a generic definition (e.g. 'what is a premium?'),
            - it is a fully self-contained calculation with all values given inline, OR
            - it ALREADY CONTAINS A SPECIFIC PROPER PLAN/POLICY NAME anywhere in the
            question text itself (e.g. 'Progress Legacy Plan', 'My Choice Enhanced
            Family Funeral Plan') — in this case the question is about THAT named
            plan, regardless of which plan was discussed previously in context.
    
            HARD RULE — ALWAYS USE THE MOST RECENT PLAN, BUT ONLY WHEN THE QUESTION
            DOESN'T NAME ONE ITSELF: If the question is context-dependent per Step 1
            (i.e. uses a generic/bare reference with no proper name of its own), and
            the context mentions more than one plan/policy across turns, resolve to
            the plan/policy discussed MOST RECENTLY — closest to the end of context,
            nearest to the current question. This has NO exceptions in that case.
            However, this rule NEVER applies if the question already names a
            specific plan itself — a named plan in the question always wins over
            context recency, full stop.
    
            Step 2:
            - If NOT context-dependent (including when the question already names
            its own plan): return the ORIGINAL question EXACTLY as given — no
            changes at all.
            - If context-dependent: rewrite by inserting the SPECIFIC NAME of the
            MOST RECENTLY discussed plan/policy, exactly as it appears in context.
            Do NOT use generic placeholders like 'the plan'. Change only what's
            needed — do not rephrase anything else.
    
            Examples:
            Context: 'Turn 1 - User asked about My Choice Enhanced Family Funeral
            Plan. Turn 2 - User asked about Flexible Life Plan.'
            Question: 'tell me about Progress Legacy Plan'
            Output: 'tell me about Progress Legacy Plan'
            (The question names its own specific plan — Progress Legacy Plan — so
            it stays unchanged, even though Flexible Life Plan is the most recent
            plan in context. A named plan in the question always wins.)
    
            Context: 'Turn 1 - User asked about My Choice Enhanced Family Funeral
            Plan. Turn 2 - User asked about Flexible Life Plan.'
            Question: 'what are the benefits?'
            Output: 'What are the benefits of the Flexible Life Plan?'
            (No plan named in the question — generic reference — resolve to most
            recent plan in context.)
    
            Context: 'Turn 1 - User asked about My Choice Enhanced Family Funeral
            Plan. Turn 2 - User asked about Flexible Life Plan.'
            Question: 'plan cover accidental death?'
            Output: 'Does the Flexible Life Plan cover accidental death?'
            WRONG output (do not do this): 'Does the My Choice Enhanced Family
            Funeral Plan cover accidental death?'
            WRONG output (do not do this): 'Does the plan cover accidental death?'
    
            Context: 'User asked about the Flexible Life Plan.'
            Question: 'What are the benefits of the My Choice Enhanced Family
            Funeral Plan?'
            Output: 'What are the benefits of the My Choice Enhanced Family Funeral
            Plan?'
    
            Context: 'User asked about the Flexible Life Plan.'
            Question: 'what plans are there?'
            Output: 'what plans are there?'
    
            Context: 'User asked about the Flexible Life Plan.'
            Question: 'what is a premium?'
            Output: 'what is a premium?'
    
            Return ONLY the final question (rewritten or original), nothing else —
            no explanation, no labels, no quotes around it.
            TXT;
        return trim($this->azure->chat([
            ['role' => 'system', 'content' => "You are a query disambiguation assistant. Your job is to determine whether a question's answer depends on prior conversation context because it uses a GENERIC reference (e.g. 'the plan', 'benefits?') with no proper name of its own. When that happens and context mentions multiple plans, ALWAYS resolve to the MOST RECENTLY discussed one, with no exceptions. However, if the question ALREADY NAMES a specific plan or policy itself, never override it — return it unchanged, even if that named plan differs from the most recent one in context. If the question is general, self-contained, or a standalone calculation, return it unchanged. Return only the final question text — no explanation, no labels, no quotes."],
            ['role' => 'user', 'content' => $prompt],
        ]));
    }

    // ── GetQNASearchChatResponse (ChatbotQNA.cs:785) — orchestrator ────────
    /**
     * @param  array{Question?:string,SessionId?:string}  $request
     * @return array<string,mixed>  QueryResp
     */
    public function getQnaSearchChatResponse(array $request): array
    {
        $finalResponse = ['QueryDocumentDetails' => [], 'Answer' => null];
        $question = (string) ($request['Question'] ?? '');
        $sessionId = (string) ($request['SessionId'] ?? '');

        $chatBotId = 1;
        $isMultiTurnEnabled = true;
        $multiTurnCount = 5;

        $chatbotRole = (string) (CompanyChatbot::where('companychatbot_id', $chatBotId)->value('chatbotrole') ?? '');

        $chatbotPrompt = $this->getNormalQuestionPrompt($chatbotRole);
        $genericPrompt = (string) config('prompts.generic');

        // Multi-turn: rewrite the query for retrieval accuracy.
        $rewrittenQuery = $question;
        if ($isMultiTurnEnabled && $multiTurnCount > 0) {
            $recentSummaries = $this->getLastNSummaryContexts($sessionId, $multiTurnCount);
            if (count($recentSummaries) > 0) {
                $rewrittenQuery = $this->rewriteQuestionFromSummaries($question, $recentSummaries);
            }
        }

        // Composite-question splitting.
        $queries = $this->getSingleQuestionFromComposite($chatBotId, $rewrittenQuery);

        $mergedSingleQuestion = implode(' ', array_map(static function ($q) {
            return (str_ends_with($q, '?') || str_ends_with($q, '.')) ? $q : $q.'?';
        }, $queries));

        // Embed each sub-question.
        $queryEmbeddingsList = [];
        foreach ($queries as $q) {
            $queryEmbeddingsList[] = $this->azure->embed($q);
        }

        // Retrieve + re-rank.
        $documentChunksList = $this->getSimilarChunks($rewrittenQuery, $queryEmbeddingsList, $chatBotId);

        // Merge chunk content + collect distinct docs.
        $documentContent = '';
        $distinctDocuments = [];
        foreach ($documentChunksList as $docContentResults) {
            $seenUrls = [];
            $distinctDocuments = [];
            foreach ($docContentResults as $doc) {
                $url = $doc['documenturl'];
                if (! isset($seenUrls[$url])) {
                    $seenUrls[$url] = true;
                    $distinctDocuments[] = $doc;
                }
            }
            if (count($docContentResults) > 0) {
                $documentContent = $this->getDocumentMergeContent($docContentResults, $documentContent);
            }
        }

        $documentContentResultCount = array_sum(array_map('count', $documentChunksList));

        // Generate the answer (fetches multi-turn context + saves history internally).
        $qnaResponseOutput = $this->getLlmResponse(
            $chatBotId,
            $rewrittenQuery,
            $documentContent,
            $documentContentResultCount,
            $chatbotPrompt,
            $genericPrompt,
            $chatbotRole,
            $sessionId,
            $isMultiTurnEnabled,
            $multiTurnCount
        );

        // Build the final response.
        if (count($distinctDocuments) > 0 && ($qnaResponseOutput['AnswerFlag'] ?? 0) > 0) {
            $finalResponse = $this->getQnaResponse($mergedSingleQuestion, $qnaResponseOutput, $distinctDocuments, $sessionId);
        } else {
            $finalResponse['QueryDocumentDetails'] = [];
            $finalResponse['Answer'] = $qnaResponseOutput['LLMAnswer'] ?? null;
        }

        return $finalResponse;
    }
}
