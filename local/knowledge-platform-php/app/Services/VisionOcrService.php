<?php

namespace App\Services;

use GuzzleHttp\Client;
use RuntimeException;

/**
 * Port of DocumentIngestion.GetImageRawText (BAL/Classes/DocumentIngestion.cs:479).
 *
 * Calls the GPT-4 Vision (GPT-4o) chat/completions endpoint via raw HTTP with a
 * base64 image + the vision system prompt, returning the model's description
 * (or an empty string for non-explainable images). Manual 429 backoff/retry,
 * matching the .NET loop (backoff = delay * 2^attempt).
 */
class VisionOcrService
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 180]);
    }

    public function describe(string $imageBase64): string
    {
        $initialDelay = (int) config('knowledge.delay_in_seconds');
        $maxRetries = (int) config('knowledge.max_retries');

        $key = (string) config('knowledge.gpt4v.key');
        $prompt = (string) config('prompts.gpt4v');
        $endpoint = (string) config('knowledge.gpt4v.endpoint');

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            $response = $this->http->post($endpoint, [
                'headers' => [
                    'api-key' => $key,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messages' => [[
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'image_url',
                                'image_url' => ['url' => "data:image/jpeg;base64,{$imageBase64}"],
                            ],
                            [
                                'type' => 'text',
                                'text' => $prompt,
                            ],
                        ],
                    ]],
                    'temperature' => 0.7,
                    'top_p' => 0.95,
                    'max_tokens' => 2000,
                    'stream' => false,
                ],
                'http_errors' => false,
            ]);

            $status = $response->getStatusCode();

            if ($status >= 200 && $status < 300) {
                $data = json_decode((string) $response->getBody(), true);
                $ocr = trim((string) ($data['choices'][0]['message']['content'] ?? ''));

                // Handle model returning a quoted empty string.
                if ($ocr === '""' || $ocr === "''") {
                    $ocr = '';
                }

                // Handle accidental surrounding double quotes.
                if ($ocr !== '' && str_starts_with($ocr, '"') && str_ends_with($ocr, '"')) {
                    $ocr = trim($ocr, '"');
                }

                return $ocr;
            }

            if ($status === 429) {
                $backoff = $initialDelay * (2 ** $attempt);
                $jitterMs = random_int(500, 1500);
                usleep(($backoff * 1000 + $jitterMs) * 1000);
                continue;
            }

            // Non-success, non-429: the .NET code swallows the exception and retries.
        }

        throw new RuntimeException('Failed to OCR the data after maximum retries.');
    }
}
