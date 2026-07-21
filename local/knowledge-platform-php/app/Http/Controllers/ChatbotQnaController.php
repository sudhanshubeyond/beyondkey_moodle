<?php

namespace App\Http\Controllers;

use App\Services\ChatbotQnaService;
use App\Services\ErrorLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * Port of KnowledgePlatform/Controllers/ChatbotQNAController.cs.
 *
 * Responses match the .NET wire format: ASP.NET Core serializes with the
 * camelCase naming policy, so CommonOutput<QueryResp> => { status, message,
 * data: { queryDocumentDetails: [{ documentName, documentURL, pageNumber }],
 * answer } } and DeleteStatus => { status, message }.
 */
class ChatbotQnaController extends Controller
{
    public function __construct(
        private ChatbotQnaService $chatbotQna,
        private ErrorLogger $errorLogger,
    ) {
    }

    // POST /api/ChatbotQNA/GetQnAResponse
    public function getQnAResponse(Request $request): JsonResponse
    {
        try {
            // ASP.NET model binding is case-insensitive; accept both casings.
            $payload = [
                'Question' => $request->input('Question', $request->input('question', '')),
                'SessionId' => $request->input('SessionId', $request->input('sessionId', '')),
            ];

            $queryResp = $this->chatbotQna->getQnaSearchChatResponse($payload);

            return response()->json([
                'status' => 'true',
                'message' => 'Success',
                'data' => $this->shapeQueryResp($queryResp),
            ]);
        } catch (Throwable $ex) {
            $this->safeLog($ex, 'ChatbotQNAController');

            return response()->json([
                'status' => 'false',
                'message' => 'Failed',
                'data' => null,
            ]);
        }
    }

    // GET /api/ChatbotQNA/DeleteQNAHistory?sessionId=...
    public function deleteQNAHistory(Request $request): JsonResponse
    {
        $deleteStatus = ['Status' => null, 'Message' => null];

        try {
            $sessionId = $request->query('sessionId');
            $deleteStatus = $this->chatbotQna->deleteQnaSessionHistory($sessionId);
        } catch (Throwable $ex) {
            $this->safeLog($ex, 'ChatbotQNAController');
        }

        return response()->json([
            'status' => $deleteStatus['Status'] ?? null,
            'message' => $deleteStatus['Message'] ?? null,
        ]);
    }

    /**
     * @param  array<string,mixed>  $queryResp
     * @return array<string,mixed>
     */
    private function shapeQueryResp(array $queryResp): array
    {
        $docs = array_map(static fn ($d) => [
            'documentName' => $d['DocumentName'] ?? null,
            'documentURL' => $d['DocumentURL'] ?? null,
            'pageNumber' => $d['PageNumber'] ?? null,
        ], $queryResp['QueryDocumentDetails'] ?? []);

        return [
            'queryDocumentDetails' => $docs,
            'answer' => $queryResp['Answer'] ?? null,
        ];
    }

    private function safeLog(Throwable $ex, string $source): void
    {
        try {
            $this->errorLogger->logError($ex, $source, '', '', '', '');
        } catch (Throwable) {
            // Never let logging failures mask the original response.
        }
    }
}
