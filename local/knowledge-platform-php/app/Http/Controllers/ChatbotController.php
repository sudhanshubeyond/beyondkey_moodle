<?php

namespace App\Http\Controllers;

use App\Services\CompanyChatbotService;
use App\Services\ErrorLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * Port of KnowledgePlatform/Controllers/ChatbotController.cs.
 */
class ChatbotController extends Controller
{
    public function __construct(
        private CompanyChatbotService $companyChatbot,
        private ErrorLogger $errorLogger,
    ) {
    }

    // POST /api/Chatbot/CreateChatbot
    public function createChatbot(Request $request): JsonResponse
    {
        try {
            $model = [
                'chatbot_name' => $request->input('chatbot_name', $request->input('ChatbotName')),
                'chatbotrole' => $request->input('chatbotrole', $request->input('ChatbotRole')),
                'issuggestedquestionsenable' => $request->boolean(
                    'issuggestedquestionsenable',
                    $request->boolean('IsSuggestedQuestionsEnable', true)
                ),
                'UserEmail' => $request->input('UserEmail', $request->input('userEmail', '')),
            ];

            $result = $this->companyChatbot->createChatbot($model);

            if (($result['ChatbotId'] ?? 0) > 0) {
                return response()->json([
                    'status' => 'true',
                    'message' => 'Chatbot created successfully.',
                    'data' => ['chatbotId' => $result['ChatbotId']],
                ]);
            }

            return response()->json([
                'status' => 'false',
                'message' => 'Failed to create chatbot.',
                'data' => null,
            ]);
        } catch (Throwable $ex) {
            $this->safeLog($ex, 'CompanyChatbotController', $request->input('UserEmail', ''));

            return response()->json([
                'status' => 'false',
                'message' => 'An error occurred while creating chatbot.',
                'data' => null,
            ]);
        }
    }

    // GET /api/Chatbot/GetChatbot
    public function getChatbot(): JsonResponse
    {
        $chatbot = $this->companyChatbot->getChatbot();

        // Return the entity in the same (lowercase) column shape the .NET
        // camelCase policy produces for already-lowercase property names.
        return response()->json($chatbot?->toArray() ?? []);
    }

    private function safeLog(Throwable $ex, string $source, string $userEmail): void
    {
        try {
            $this->errorLogger->logError($ex, $source, '', '', '', $userEmail);
        } catch (Throwable) {
            //
        }
    }
}
