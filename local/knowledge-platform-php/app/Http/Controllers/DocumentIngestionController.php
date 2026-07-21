<?php

namespace App\Http\Controllers;

use App\Jobs\IngestDocumentsJob;
use App\Services\DocumentIngestionService;
use App\Services\ErrorLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

/**
 * Port of KnowledgePlatform/Controllers/DocumentIngestionController.cs.
 */
class DocumentIngestionController extends Controller
{
    public function __construct(
        private DocumentIngestionService $documentIngestion,
        private ErrorLogger $errorLogger,
    ) {
    }

    // POST /api/DocumentIngestion/TriggerDocumentIngestion  (multipart)
    public function triggerDocumentIngestion(Request $request): JsonResponse
    {
        try {
            // Accept "Documents" / "Documents[]" / "documents".
            $documents = $request->file('Documents', $request->file('documents', []));
            if (! is_array($documents)) {
                $documents = [$documents];
            }
            $documents = array_filter($documents);

            if (count($documents) === 0) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'No documents provided for ingestion.',
                    'data' => null,
                ]);
            }

            $model = [
                'ChatbotID' => (int) $request->input('ChatbotID', $request->input('chatbotID', 0)),
                'DocumentURL' => $request->input('DocumentURL', $request->input('documentURL')),
                'UserEmail' => (string) $request->input('UserEmail', $request->input('userEmail', '')),
            ];

            // Stage the uploaded files on disk (CreateDocumentsMemoryStream equivalent).
            $stagingDir = storage_path('app/ingestion/'.Str::uuid()->toString());
            if (! is_dir($stagingDir)) {
                mkdir($stagingDir, 0775, true);
            }

            $files = [];
            foreach ($documents as $file) {
                if ($file === null || ! $file->isValid() || $file->getSize() === 0) {
                    continue;
                }
                $extension = strtolower($file->getClientOriginalExtension());
                $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $stored = $stagingDir.DIRECTORY_SEPARATOR.Str::uuid()->toString().($extension !== '' ? '.'.$extension : '');
                $file->move($stagingDir, basename($stored));

                $files[] = [
                    'path' => $stored,
                    'name' => $baseName,
                    'extension' => $extension,
                ];
            }

            // Dispatch to the queue (replaces .NET Task.Run fire-and-forget).
            IngestDocumentsJob::dispatch($model, $files, $stagingDir);

            return response()->json([
                'status' => 'true',
                'message' => 'Data ingestion initiated successfully.',
                'data' => null,
            ]);
        } catch (Throwable $ex) {
            $this->safeLog($ex, 'TriggerDocumentIngestion', (string) $request->input('UserEmail', ''));

            return response()->json([
                'status' => 'false',
                'message' => 'Failed to initiate data ingestion.',
                'data' => null,
            ]);
        }
    }

    // POST /api/DocumentIngestion/CreateGreetingQuestionsEmbeddings
    public function createGreetingQuestionsEmbeddings(): JsonResponse
    {
        $this->documentIngestion->createGreetingQuestions();

        // The .NET action returns void (HTTP 200, empty body).
        return response()->json(null);
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
