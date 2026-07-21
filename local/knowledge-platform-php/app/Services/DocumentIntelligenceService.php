<?php

namespace App\Services;

use GuzzleHttp\Client;
use RuntimeException;

/**
 * Replaces Azure.AI.DocumentIntelligence (DocumentIntelligenceClient) with the
 * REST API. The .NET SDK's `AnalyzeDocumentAsync(WaitUntil.Completed, model, data)`
 * is the submit → poll `Operation-Location` → read `analyzeResult` lifecycle,
 * reproduced here.
 *
 * Uses the 2024-11-30 (v4.0 GA) API:
 *   POST {endpoint}/documentintelligence/documentModels/{model}:analyze?api-version=...
 */
class DocumentIntelligenceService
{
    private Client $http;
    private string $endpoint;
    private string $key;
    private string $apiVersion;

    public function __construct()
    {
        $cfg = config('knowledge.document_intelligence');
        $this->endpoint = (string) $cfg['endpoint'];
        $this->key = (string) $cfg['key'];
        $this->apiVersion = (string) $cfg['api_version'];

        $this->http = new Client(['timeout' => 300]);
    }

    /**
     * Analyze raw document bytes with the given prebuilt model and return the
     * `analyzeResult` object as an associative array.
     *
     * @return array<string,mixed>
     */
    public function analyze(string $modelId, string $bytes): array
    {
        if ($this->endpoint === '' || $this->key === '') {
            throw new RuntimeException('PDF extraction configuration missing.');
        }

        $submitUrl = sprintf(
            '%s/documentintelligence/documentModels/%s:analyze?api-version=%s',
            $this->endpoint,
            rawurlencode($modelId),
            $this->apiVersion
        );

        $submit = $this->http->post($submitUrl, [
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->key,
                'Content-Type' => 'application/octet-stream',
            ],
            'body' => $bytes,
            'http_errors' => false,
        ]);

        $status = $submit->getStatusCode();
        if ($status !== 202 && $status !== 200) {
            throw new RuntimeException("Document Intelligence submit failed with HTTP {$status}: ".$submit->getBody());
        }

        $operationLocation = $submit->getHeaderLine('Operation-Location');
        if ($operationLocation === '') {
            throw new RuntimeException('Document Intelligence did not return an Operation-Location.');
        }

        // Poll until the operation succeeds (or fails). Mirrors WaitUntil.Completed.
        $maxPolls = 300; // ~5 min at 1s intervals
        for ($i = 0; $i < $maxPolls; $i++) {
            $poll = $this->http->get($operationLocation, [
                'headers' => ['Ocp-Apim-Subscription-Key' => $this->key],
                'http_errors' => false,
            ]);

            if ($poll->getStatusCode() === 429) {
                usleep(1_000_000);
                continue;
            }

            if ($poll->getStatusCode() < 200 || $poll->getStatusCode() >= 300) {
                throw new RuntimeException('Document Intelligence poll failed with HTTP '.$poll->getStatusCode().': '.$poll->getBody());
            }

            $data = json_decode((string) $poll->getBody(), true);
            $opStatus = strtolower((string) ($data['status'] ?? ''));

            if ($opStatus === 'succeeded') {
                return $data['analyzeResult'] ?? [];
            }
            if ($opStatus === 'failed') {
                throw new RuntimeException('Document Intelligence analysis failed: '.json_encode($data['error'] ?? []));
            }

            usleep(1_000_000); // running / notStarted → wait 1s
        }

        throw new RuntimeException('Document Intelligence analysis timed out.');
    }
}
