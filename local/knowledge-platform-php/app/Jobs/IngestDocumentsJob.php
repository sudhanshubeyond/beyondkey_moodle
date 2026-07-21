<?php

namespace App\Jobs;

use App\Services\DocumentIngestionService;
use App\Services\ErrorLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * Background document ingestion — replaces the .NET fire-and-forget
 * `Task.Run(...)` in DocumentIngestionController.TriggerDocumentIngestion.
 *
 * Requires a running queue worker (`php artisan queue:work`).
 *
 * The uploaded files are staged on disk by the controller; this job receives
 * their paths + metadata, runs the pipeline, then cleans up the staging dir.
 */
class IngestDocumentsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** No automatic retries — mirrors the one-shot .NET background task. */
    public $tries = 1;

    public $timeout = 3600;

    /**
     * @param  array{ChatbotID:int,UserEmail:string,DocumentURL:?string}  $model
     * @param  array<int,array{path:string,name:string,extension:string}>  $files
     */
    public function __construct(
        private array $model,
        private array $files,
        private ?string $stagingDir = null,
    ) {
    }

    public function handle(DocumentIngestionService $ingestion, ErrorLogger $errorLogger): void
    {
        try {
            $ingestion->addDocuments($this->model, $this->files);
        } catch (Throwable $ex) {
            $errorLogger->logError($ex, 'TriggerDocumentIngestion', '', '', '', (string) ($this->model['UserEmail'] ?? ''));
        } finally {
            $this->cleanup();
        }
    }

    private function cleanup(): void
    {
        foreach ($this->files as $file) {
            if (isset($file['path']) && is_file($file['path'])) {
                @unlink($file['path']);
            }
        }
        if ($this->stagingDir !== null && is_dir($this->stagingDir)) {
            @rmdir($this->stagingDir);
        }
    }
}
