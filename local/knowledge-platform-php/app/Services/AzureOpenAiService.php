<?php

namespace App\Services;

use App\Services\Exceptions\AzureRateLimitException;
use GuzzleHttp\Client;
use InvalidArgumentException;
use RuntimeException;

/**
 * Replaces Azure.AI.OpenAI (AzureOpenAIClient) and Semantic Kernel's
 * IChatCompletionService with direct REST calls (same wire protocol).
 *
 * - chat()  → POST {endpoint}/openai/deployments/{chat_model}/chat/completions
 * - embed() → POST {endpoint}/openai/deployments/{embedding_model}/embeddings
 *
 * The 429 retry loops that wrap chat() live in the calling services (as in the
 * .NET GetLLMResponse). embed() carries its own retry loop, matching
 * DocumentIngestion.GetEmbeddingData.
 */
class AzureOpenAiService
{
    private Client $http;
    private string $endpoint;
    private string $key;
    private string $chatModel;
    private string $embeddingModel;
    private string $apiVersion;

    public function __construct(private ErrorLogger $errorLogger)
    {
        $cfg = config('knowledge.azure_openai');

        $this->endpoint = (string) $cfg['endpoint'];
        $this->key = (string) $cfg['key'];
        $this->chatModel = (string) $cfg['chat_model'];
        $this->embeddingModel = (string) $cfg['embedding_model'];
        $this->apiVersion = (string) $cfg['api_version'];

        $this->http = new Client(['timeout' => 180]);
    }

    /**
     * Chat completion. Returns the concatenated assistant text.
     *
     * @param  array<int,array{role:string,content:mixed}>  $messages
     * @param  array<string,mixed>  $options  e.g. ['temperature'=>0.5,'top_p'=>0.9]
     *
     * @throws AzureRateLimitException on HTTP 429 (callers drive backoff)
     */
    public function chat(array $messages, array $options = []): string
    {
        $url = sprintf(
            '%s/openai/deployments/%s/chat/completions?api-version=%s',
            $this->endpoint,
            rawurlencode($this->chatModel),
            $this->apiVersion
        );

        $payload = array_merge(['messages' => $messages], $options);

        $response = $this->http->post($url, [
            'headers' => [
                'api-key' => $this->key,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
            'http_errors' => false,
        ]);

        $status = $response->getStatusCode();

        if ($status === 429) {
            throw new AzureRateLimitException('Azure OpenAI chat rate limited (429).');
        }

        if ($status < 200 || $status >= 300) {
            throw new RuntimeException("Azure OpenAI chat failed with HTTP {$status}: ".$response->getBody());
        }

        $data = json_decode((string) $response->getBody(), true);

        $parts = $data['choices'][0]['message']['content'] ?? '';

        // Azure may return content as a string (normal) — return as-is.
        return is_string($parts) ? $parts : '';
    }

    /**
     * Convenience: single-prompt chat (mirrors Semantic Kernel's
     * GetChatMessageContentAsync(prompt) used by GetAnswerSummary).
     */
    public function complete(string $prompt): string
    {
        return $this->chat([
            ['role' => 'user', 'content' => $prompt],
        ]);
    }

    /**
     * Generate a 1536-dim embedding for the given text.
     *
     * Faithful port of DocumentIngestion.GetEmbeddingData: validates non-empty
     * input, retries up to MAX_RETRIES on HTTP 429 waiting DELAY_IN_SECONDS
     * between attempts, logs each 429 to the errorlog table.
     *
     * @return array<int,float>
     */
    public function embed(string $textData): array
    {
        if (trim($textData) === '') {
            throw new InvalidArgumentException('Text data is empty.');
        }

        $delayMs = ((int) config('knowledge.delay_in_seconds')) * 1000;
        $retries = (int) config('knowledge.max_retries');

        if ($this->endpoint === '' || $this->key === '' || $this->embeddingModel === '') {
            throw new RuntimeException('Azure OpenAI configuration missing.');
        }

        $url = sprintf(
            '%s/openai/deployments/%s/embeddings?api-version=%s',
            $this->endpoint,
            rawurlencode($this->embeddingModel),
            $this->apiVersion
        );

        for ($i = 0; $i < $retries; $i++) {
            $response = $this->http->post($url, [
                'headers' => [
                    'api-key' => $this->key,
                    'Content-Type' => 'application/json',
                ],
                'json' => ['input' => $textData],
                'http_errors' => false,
            ]);

            $status = $response->getStatusCode();

            if ($status === 429) {
                $this->errorLogger->logError(
                    new RuntimeException('Azure OpenAI embedding rate limited (429).'),
                    'Embedding'
                );
                usleep($delayMs * 1000);
                continue;
            }

            if ($status < 200 || $status >= 300) {
                throw new RuntimeException("Azure OpenAI embedding failed with HTTP {$status}: ".$response->getBody());
            }

            $data = json_decode((string) $response->getBody(), true);
            $embedding = $data['data'][0]['embedding'] ?? null;

            if (! is_array($embedding)) {
                throw new RuntimeException('Azure OpenAI embedding response missing data.');
            }

            return array_map('floatval', $embedding);
        }

        throw new RuntimeException('Embedding generation failed after retries.');
    }
}
