<?php

/*
|--------------------------------------------------------------------------
| Knowledge Platform configuration
|--------------------------------------------------------------------------
| 1:1 mapping of the .NET appsettings.json (minus the PromptContext block,
| which lives in config/prompts.php). Values are read from .env.
*/

return [

    // Custom API-key auth (X-Api-Key header) — appsettings.json "AuthApiKey"
    'auth_api_key' => env('AUTH_API_KEY'),

    // Azure OpenAI (chat completions + embeddings)
    'azure_openai' => [
        'endpoint' => rtrim((string) env('AZURE_OPENAI_ENDPOINT'), '/'),
        'key' => env('AZURE_OPENAI_KEY'),
        'chat_model' => env('AZURE_OPENAI_CHAT_MODEL', 'gpt-4o'),
        'embedding_model' => env('AZURE_OPENAI_EMBEDDING_MODEL', 'text-embedding-ada-002'),
        'api_version' => env('AZURE_OPENAI_API_VERSION', '2025-01-01-preview'),
    ],

    // Azure Document Intelligence (prebuilt-layout / prebuilt-read)
    'document_intelligence' => [
        'endpoint' => rtrim((string) env('DOC_INTELLIGENCE_ENDPOINT'), '/'),
        'key' => env('DOC_INTELLIGENCE_KEY'),
        'api_version' => env('DOC_INTELLIGENCE_API_VERSION', '2024-11-30'),
    ],

    // GPT-4 Vision OCR (full chat/completions URL incl. its own api-version)
    'gpt4v' => [
        'endpoint' => env('GPT4V_ENDPOINT'),
        'key' => env('GPT4V_KEY'),
    ],

    // RAG tuning knobs
    'chunk_size' => (int) env('CHUNK_SIZE', 500),
    'overlap' => (int) env('OVERLAP', 100),
    'pick_chunks_composite' => (int) env('PICK_CHUNKS_COMPOSITE', 2),
    'pick_chunks_single' => (int) env('PICK_CHUNKS_SINGLE', 3),
    'similarity_threshold' => (float) env('SIMILARITY_THRESHOLD', 0.72),
    'delay_in_seconds' => (int) env('DELAY_IN_SECONDS', 50),
    'max_retries' => (int) env('MAX_RETRIES', 5),

    // Ghostscript binary for spatie/pdf-to-image (optional; auto-detected if on PATH)
    'ghostscript_path' => env('GHOSTSCRIPT_PATH'),

];
