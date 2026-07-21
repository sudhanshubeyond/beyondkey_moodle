<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ChatbotQnaController;
use App\Http\Controllers\DocumentIngestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
| Mirrors the .NET "api/[controller]/[action]" convention. All routes are
| guarded by the X-Api-Key middleware (= [ServiceFilter(ApiKeyAuthenticationFilter)]).
| Laravel prefixes this file with "/api" automatically.
*/

Route::middleware('apikey')->group(function () {

    // ── ChatbotQNAController ──────────────────────────────────────────────
    Route::post('ChatbotQNA/GetQnAResponse', [ChatbotQnaController::class, 'getQnAResponse']);
    Route::get('ChatbotQNA/DeleteQNAHistory', [ChatbotQnaController::class, 'deleteQNAHistory']);

    // ── DocumentIngestionController ───────────────────────────────────────
    Route::post('DocumentIngestion/TriggerDocumentIngestion', [DocumentIngestionController::class, 'triggerDocumentIngestion']);
    Route::post('DocumentIngestion/CreateGreetingQuestionsEmbeddings', [DocumentIngestionController::class, 'createGreetingQuestionsEmbeddings']);

    // ── ChatbotController ─────────────────────────────────────────────────
    Route::post('Chatbot/CreateChatbot', [ChatbotController::class, 'createChatbot']);
    Route::get('Chatbot/GetChatbot', [ChatbotController::class, 'getChatbot']);
});
