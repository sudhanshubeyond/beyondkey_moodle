<?php

namespace App\Services;

use App\Models\CompanyChatbot;
use InvalidArgumentException;
use RuntimeException;

/**
 * Port of BAL/Classes/CompanyChatbotDetails.cs.
 */
class CompanyChatbotService
{
    // GetChatbot — first companychatbot row.
    public function getChatbot(): ?CompanyChatbot
    {
        return CompanyChatbot::query()->first();
    }

    /**
     * CreateChatbot — insert a new chatbot; returns the new id.
     *
     * @param  array<string,mixed>  $model  CompanyChatbotViewModel
     * @return array{ChatbotId:int}
     */
    public function createChatbot(array $model): array
    {
        $chatbotName = trim((string) ($model['chatbot_name'] ?? ''));
        $userEmail = trim((string) ($model['UserEmail'] ?? ''));

        if ($chatbotName === '') {
            throw new InvalidArgumentException('Chatbot name is required.');
        }
        if ($userEmail === '') {
            throw new InvalidArgumentException('User email is required.');
        }

        $entity = CompanyChatbot::create([
            'chatbot_name' => $chatbotName,
            'chatbotrole' => isset($model['chatbotrole']) ? trim((string) $model['chatbotrole']) : null,
            'issuggestedquestionsenable' => (bool) ($model['issuggestedquestionsenable'] ?? true),
            'createdby' => $userEmail,
            'modifiedby' => $userEmail,
            'createdat' => now('UTC'),
            'modifiedat' => now('UTC'),
        ]);

        $chatbotId = (int) $entity->companychatbot_id;

        if ($chatbotId <= 0) {
            throw new RuntimeException('Failed to create chatbot.');
        }

        return ['ChatbotId' => $chatbotId];
    }
}
