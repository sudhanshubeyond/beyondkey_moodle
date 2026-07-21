<?php

namespace App\Services;

use App\Models\DocumentDetails;
use App\Models\DocumentIngestionStatus;
use App\Support\PgVector;
use App\Support\ProcessingStatus;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\DB;
use Imagick;
use RuntimeException;
use Throwable;
use ZipArchive;

/**
 * Port of BAL/Classes/DocumentIngestion.cs.
 *
 * Reproduces the full ingestion pipeline: text/image extraction (Azure
 * Document Intelligence + GPT-4V), word-based chunking with overlap, embedding
 * generation, per-document transactional persistence with status tracking, and
 * greeting-question seeding.
 *
 * A "document file" is represented as: ['path'=>string, 'name'=>string,
 * 'extension'=>string] where name is the base filename without extension and
 * extension is lower-cased (matching CreateDocumentsMemoryStream).
 */
class DocumentIngestionService
{
    public function __construct(
        private AzureOpenAiService $azure,
        private DocumentIntelligenceService $docIntel,
        private VisionOcrService $vision,
        private ErrorLogger $errorLogger,
    ) {
    }

    // ── GetEmbeddingData (DocumentIngestion.cs:42) ─────────────────────────
    /** @return array<int,float> */
    public function getEmbeddingData(string $textData): array
    {
        return $this->azure->embed($textData);
    }

    // ── DocumentChuncks (DocumentIngestion.cs:92) ──────────────────────────
    /**
     * Build one chunk row. Returns an array carrying the pgvector literal in
     * `embedding` (inserted with ::vector later), or null embedding for empty text.
     *
     * @return array<string,mixed>
     */
    public function documentChunk(int $documentId, string $textData, int $pageNumber): array
    {
        $entity = [
            'document_id' => $documentId,
            'pagenumber' => $pageNumber,
            'textdata' => null,
            'embedding' => null,
            'createdat' => now('UTC'),
            'modifiedat' => now('UTC'),
        ];

        if (trim($textData) !== '') {
            $sanitized = str_replace("\0", '', $textData);
            $embedding = $this->getEmbeddingData($sanitized);
            $entity['textdata'] = $sanitized;
            $entity['embedding'] = PgVector::toLiteral($embedding);
        }

        return $entity;
    }

    // ── SaveDocumentDetailsInfo (DocumentIngestion.cs:123) ─────────────────
    public function saveDocumentDetailsInfo(
        string $documentName,
        string $documentType,
        int $documentTotalPage,
        int $chatbotId,
        string $userEmail,
        ?string $documentURL
    ): int {
        if (trim($documentName) === '') {
            throw new RuntimeException('Document name missing.');
        }

        $entity = DocumentDetails::create([
            'documentname' => $documentName,
            'documenttype' => $documentType,
            'documenttotalpage' => $documentTotalPage,
            'companychatbot_id' => $chatbotId,
            'documenturl' => $documentURL,
            'createdby' => $userEmail,
            'createdat' => now('UTC'),
            'modifiedat' => now('UTC'),
        ]);

        $id = (int) $entity->document_id;
        if ($id <= 0) {
            throw new RuntimeException('Document ID not generated.');
        }

        return $id;
    }

    // ── CreateDocumentTextChunks (DocumentIngestion.cs:157) ────────────────
    /**
     * Word-based chunking with overlap, page-tracked, plus image-OCR chunks.
     *
     * @param  array<int,array{DocumentPageNumber:int,DocumentOCRText:string}>  $documentImagesList
     * @return array<int,array<string,mixed>>
     */
    public function createDocumentTextChunks(string $documentName, string $entireDocumentText, array $documentImagesList, int $documentId): array
    {
        $chunks = [];

        if (trim($entireDocumentText) === '') {
            return $chunks;
        }
        if (trim($documentName) === '') {
            throw new RuntimeException('Document name is required.');
        }

        // Split on the "Page " delimiter (RemoveEmptyEntries semantics).
        $pageWiseContent = array_values(array_filter(
            explode('Page ', $entireDocumentText),
            static fn ($s) => $s !== ''
        ));

        $chunkSize = (int) config('knowledge.chunk_size');
        $overlap = (int) config('knowledge.overlap');
        $remainingWordsToFill = 0;
        $chunkBuilder = '';
        $chunkStartPage = 0;

        foreach ($pageWiseContent as $pageContent) {
            $colonIndex = strpos($pageContent, ':');
            if ($colonIndex === false) {
                continue;
            }

            $pageNumberPart = trim(substr($pageContent, 0, $colonIndex));
            if (! preg_match('/^-?\d+$/', $pageNumberPart)) {
                continue;
            }
            $currentPageNumber = (int) $pageNumberPart;

            $text = trim(substr($pageContent, $colonIndex + 1));
            $words = explode(' ', $text);
            $totalWords = count($words);
            $currentWordIndex = 0;

            while ($currentWordIndex < $totalWords) {
                if ($chunkBuilder === '') {
                    $chunkStartPage = $currentPageNumber;
                }

                $wordsToTake = min($chunkSize - $remainingWordsToFill, $totalWords - $currentWordIndex);

                $slice = array_slice($words, $currentWordIndex, $wordsToTake);
                $chunkBuilder .= implode(' ', $slice).' ';
                $remainingWordsToFill += $wordsToTake;
                $currentWordIndex += $wordsToTake;

                if ($remainingWordsToFill >= $chunkSize) {
                    $chunks[] = $this->documentChunk($documentId, trim($chunkBuilder), $chunkStartPage);
                    $chunkBuilder = '';
                    $remainingWordsToFill = 0;
                    $currentWordIndex = max(0, $currentWordIndex - $overlap);
                }
            }
        }

        if ($chunkBuilder !== '') {
            $chunks[] = $this->documentChunk($documentId, trim($chunkBuilder), $chunkStartPage);
        }

        // Append image-OCR chunks.
        foreach ($documentImagesList as $img) {
            $chunks[] = $this->documentChunk($documentId, (string) $img['DocumentOCRText'], (int) $img['DocumentPageNumber']);
        }

        return $chunks;
    }

    // ── GetExtractedPDFText (DocumentIngestion.cs:409) ─────────────────────
    /** @param array<string,mixed> $analyzeResult */
    public function getExtractedPdfText(array $analyzeResult): string
    {
        $out = '';
        foreach (($analyzeResult['pages'] ?? []) as $page) {
            $pageNumber = (int) ($page['pageNumber'] ?? 0);
            $lines = $page['lines'] ?? [];
            if (count($lines) === 0) {
                continue;
            }

            $pageText = '';
            foreach ($lines as $line) {
                $content = (string) ($line['content'] ?? '');
                if (trim($content) !== '') {
                    $pageText .= $content."\n";
                }
            }

            if (trim($pageText) !== '') {
                $out .= "Page {$pageNumber}: ".trim($pageText)."\n";
            }
        }

        return $out;
    }

    // ── ConvertPolygonToPixelRect (DocumentIngestion.cs:461) ───────────────
    /**
     * @param  array<int,float>  $polygon  flat [x1,y1,x2,y2,...]
     * @return array{px:int,py:int,pw:int,ph:int}
     */
    public function convertPolygonToPixelRect(array $polygon, float $pageWidth, float $pageHeight, int $imageWidth, int $imageHeight): array
    {
        $xs = [];
        $ys = [];
        foreach ($polygon as $i => $v) {
            if ($i % 2 === 0) {
                $xs[] = (float) $v;
            } else {
                $ys[] = (float) $v;
            }
        }

        $minX = min($xs);
        $maxX = max($xs);
        $minY = min($ys);
        $maxY = max($ys);

        return [
            'px' => (int) ($minX / $pageWidth * $imageWidth),
            'py' => (int) ($minY / $pageHeight * $imageHeight),
            'pw' => (int) (($maxX - $minX) / $pageWidth * $imageWidth),
            'ph' => (int) (($maxY - $minY) / $pageHeight * $imageHeight),
        ];
    }

    // ── GetPDFImagesList (DocumentIngestion.cs:597) ────────────────────────
    /**
     * Render each figure's page, crop the figure region, and describe it with GPT-4V.
     *
     * @param  array<string,mixed>  $analyzeResult
     * @return array<int,array{DocumentPageNumber:int,DocumentOCRText:string}>
     */
    public function getPdfImagesList(string $pdfPath, array $analyzeResult): array
    {
        $figures = [];
        $pages = $analyzeResult['pages'] ?? [];

        foreach (($analyzeResult['figures'] ?? []) as $figure) {
            $regions = $figure['boundingRegions'] ?? [];
            if (count($regions) === 0) {
                continue;
            }
            $region = $regions[0];
            $pageNumber = (int) ($region['pageNumber'] ?? 0);
            $polygon = $region['polygon'] ?? [];
            if ($pageNumber < 1 || count($polygon) < 4) {
                continue;
            }

            // Find the matching page for its dimensions.
            $page = null;
            foreach ($pages as $p) {
                if ((int) ($p['pageNumber'] ?? 0) === $pageNumber) {
                    $page = $p;
                    break;
                }
            }
            $pageWidth = (float) ($page['width'] ?? 1.0);
            $pageHeight = (float) ($page['height'] ?? 1.0);

            $im = null;
            try {
                // Render the figure's page at 300 DPI. Imagick page index is 0-based,
                // matching the .NET PdfiumViewer pageIndex = region.PageNumber - 1.
                $im = new Imagick();
                $im->setResolution(300, 300);
                $im->readImage($pdfPath.'['.($pageNumber - 1).']');
                $im->setImageFormat('png');

                $imgWidth = $im->getImageWidth();
                $imgHeight = $im->getImageHeight();

                $rect = $this->convertPolygonToPixelRect($polygon, $pageWidth, $pageHeight, $imgWidth, $imgHeight);

                if ($rect['pw'] > 0 && $rect['ph'] > 0) {
                    $im->cropImage($rect['pw'], $rect['ph'], $rect['px'], $rect['py']);
                }
                $im->setImageFormat('png');
                $imageBase64 = base64_encode($im->getImageBlob());

                $text = $this->vision->describe($imageBase64);

                $figures[] = [
                    'DocumentPageNumber' => $pageNumber,
                    'DocumentOCRText' => $text,
                ];
            } finally {
                if ($im instanceof Imagick) {
                    $im->clear();
                }
            }
        }

        // Drop figures with empty OCR text.
        return array_values(array_filter(
            $figures,
            static fn ($f) => trim((string) $f['DocumentOCRText']) !== ''
        ));
    }

    // ── ExtractTextAndImagesFromPdf (DocumentIngestion.cs:652) ─────────────
    /**
     * @param  array{path:string,name:string,extension:string}  $file
     * @return array<string,mixed>  DocumentCompleteDetails
     */
    public function extractTextAndImagesFromPdf(array $file): array
    {
        $bytes = file_get_contents($file['path']);
        if ($bytes === false) {
            throw new RuntimeException('Unable to read PDF file.');
        }

        $analyzeResult = $this->docIntel->analyze('prebuilt-layout', $bytes);

        if (count($analyzeResult['pages'] ?? []) === 0) {
            throw new RuntimeException('No content extracted from PDF.');
        }

        $text = $this->getExtractedPdfText($analyzeResult);
        $images = $this->getPdfImagesList($file['path'], $analyzeResult);

        return [
            'DocumentName' => $file['name'],
            'DocumentExtension' => $file['extension'],
            'DocumentTotalPages' => count($analyzeResult['pages']),
            'DocumentCombinedText' => $text,
            'DocumentImagesList' => $images,
        ];
    }

    // ── DOCX text extraction (GetExtractedDOCXText / GetDocumentMergedContent) ─
    public function getExtractedDocxText(string $docxPath): string
    {
        $documentXml = $this->readZipEntry($docxPath, 'word/document.xml');
        $entireText = $documentXml !== null ? $this->getDocumentMergedContent($documentXml) : '';

        // If scanned (has embedded images), OCR each image via Document Intelligence.
        if ($this->isDocxScanned($docxPath)) {
            foreach ($this->docxImageEntries($docxPath) as $imageBytes) {
                $imageText = $this->getDocxImagesContent($imageBytes);
                if (trim($imageText) !== '') {
                    $entireText .= "\n[IMAGE CONTENT START]\n{$imageText}\n[IMAGE CONTENT END]\n\n";
                }
            }
        }

        return $entireText;
    }

    /** Port of GetDocumentMergedContent (DocumentIngestion.cs:248). */
    public function getDocumentMergedContent(string $documentXml): string
    {
        $dom = new DOMDocument();
        // Suppress libxml warnings on OOXML markup.
        @$dom->loadXML($documentXml, LIBXML_NONET);

        $xpath = new DOMXPath($dom);
        $wNs = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
        $xpath->registerNamespace('w', $wNs);

        $bodies = $xpath->query('//w:body');
        if ($bodies === false || $bodies->length === 0) {
            return '';
        }
        $body = $bodies->item(0);

        $out = '';
        $k = 1;
        foreach ($body->childNodes as $element) {
            if ($element->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            // Skip elements that contain an explicit page break <w:br w:type="page"/>.
            $hasPageBreak = false;
            $brs = $xpath->query('.//w:br[@w:type="page"]', $element);
            if ($brs !== false && $brs->length > 0) {
                $hasPageBreak = true;
            }

            if (! $hasPageBreak) {
                $out .= "Page {$k}: ".$element->textContent."\n";
                $k++;
            }
        }

        return $out;
    }

    /** Port of IsDocxScanned (DocumentIngestion.cs:267) — image presence => scanned. */
    public function isDocxScanned(string $docxPath): bool
    {
        return count(iterator_to_array($this->docxImageEntries($docxPath))) > 0;
    }

    /**
     * Port of GetDocxImagesContent (DocumentIngestion.cs:303): normalize the
     * image (skip tiny, downscale huge) and OCR it via Document Intelligence
     * prebuilt-read, returning the extracted content.
     */
    public function getDocxImagesContent(string $imageBytes): string
    {
        if ($imageBytes === '') {
            return '';
        }

        $im = new Imagick();
        $im->readImageBlob($imageBytes);

        $minSize = 50;
        $maxSize = 4096;
        $width = $im->getImageWidth();
        $height = $im->getImageHeight();

        if ($width < $minSize || $height < $minSize) {
            $im->clear();

            return '';
        }

        if ($width > $maxSize || $height > $maxSize) {
            $im->resizeImage($maxSize, $maxSize, Imagick::FILTER_LANCZOS, 1, true); // bestfit = ResizeMode.Max
        }

        $im->setImageFormat('png');
        $png = $im->getImageBlob();
        $im->clear();

        $result = $this->docIntel->analyze('prebuilt-read', $png);

        return trim((string) ($result['content'] ?? ''));
    }

    // ── GetDocxFileImagesList (DocumentIngestion.cs:700) ───────────────────
    /** @return array<int,array{DocumentPageNumber:int,DocumentOCRText:string}> */
    public function getDocxFileImagesList(string $docxPath): array
    {
        $figures = [];
        foreach ($this->docxImageEntries($docxPath) as $imageBytes) {
            $description = $this->vision->describe(base64_encode($imageBytes));
            if (trim($description) !== '') {
                $figures[] = ['DocumentPageNumber' => 1, 'DocumentOCRText' => $description];
            }
        }

        return $figures;
    }

    // ── ExtractTextAndImagesFromDOCX (DocumentIngestion.cs:739) ────────────
    /**
     * @param  array{path:string,name:string,extension:string}  $file
     * @return array<string,mixed>  DocumentCompleteDetails
     */
    public function extractTextAndImagesFromDocx(array $file): array
    {
        $text = '';
        $figures = [];

        if ($file['extension'] === 'docx') {
            $text = $this->getExtractedDocxText($file['path']);
            $figures = $this->getDocxFileImagesList($file['path']);
        }

        return [
            'DocumentName' => $file['name'],
            'DocumentExtension' => $file['extension'],
            'DocumentTotalPages' => 1,
            'DocumentCombinedText' => $text,
            'DocumentImagesList' => $figures,
        ];
    }

    // ── AddDocumentsAsync (DocumentIngestion.cs:769) ───────────────────────
    /**
     * @param  array{ChatbotID:int,UserEmail:string,DocumentURL:?string}  $model
     * @param  array<int,array{path:string,name:string,extension:string}>  $files
     */
    public function addDocuments(array $model, array $files): void
    {
        if (count($files) === 0) {
            return;
        }
        if ((int) ($model['ChatbotID'] ?? 0) <= 0) {
            throw new RuntimeException('Invalid ChatbotID.');
        }
        if (trim((string) ($model['UserEmail'] ?? '')) === '') {
            throw new RuntimeException('UserEmail is required.');
        }

        $chatbotId = (int) $model['ChatbotID'];
        $userEmail = (string) $model['UserEmail'];
        $documentUrl = $model['DocumentURL'] ?? null;

        foreach ($files as $file) {
            $statusId = $this->createDocumentIngestionStatus($chatbotId, $file['name'], ProcessingStatus::PROCESSING);

            DB::beginTransaction();
            try {
                if (! is_file($file['path']) || filesize($file['path']) === 0) {
                    DB::commit();
                    continue;
                }

                if ($file['extension'] === 'pdf') {
                    $details = $this->extractTextAndImagesFromPdf($file);
                } elseif ($file['extension'] === 'docx' || $file['extension'] === 'doc') {
                    $details = $this->extractTextAndImagesFromDocx($file);
                } else {
                    DB::commit();
                    continue;
                }

                if (trim((string) $details['DocumentName']) === ''
                    || trim((string) $details['DocumentExtension']) === ''
                    || (int) $details['DocumentTotalPages'] <= 0
                    || trim((string) $details['DocumentCombinedText']) === '') {
                    DB::commit();
                    continue;
                }

                $documentId = $this->saveDocumentDetailsInfo(
                    $details['DocumentName'],
                    $details['DocumentExtension'],
                    (int) $details['DocumentTotalPages'],
                    $chatbotId,
                    $userEmail,
                    $documentUrl
                );

                $chunks = [];

                $metaChunk = $this->getDocumentMetaData($details['DocumentName'], $details['DocumentExtension'], $documentId);
                if ($metaChunk !== null) {
                    $chunks[] = $metaChunk;
                }

                $textChunks = $this->createDocumentTextChunks(
                    $details['DocumentName'],
                    $details['DocumentCombinedText'],
                    $details['DocumentImagesList'],
                    $documentId
                );
                $chunks = array_merge($chunks, $textChunks);

                if (count($chunks) > 0) {
                    $this->insertChunks($chunks);
                }

                $this->updateDocumentIngestionStatus($statusId, ProcessingStatus::PROCESSED);

                DB::commit();
            } catch (Throwable $ex) {
                DB::rollBack();
                $this->updateDocumentIngestionStatus($statusId, ProcessingStatus::FAILED);
                $this->errorLogger->logError($ex, 'AddDocumentsAsync', '', '', '', $userEmail);
                continue;
            }
        }
    }

    // ── GetDocumentMetaData (DocumentIngestion.cs:988) ─────────────────────
    /** @return array<string,mixed>|null */
    public function getDocumentMetaData(string $documentName, string $documentType, int $documentId): ?array
    {
        // Preserve property order: DocumentName, DocumentType, DocumentSource.
        $meta = [
            'DocumentName' => $documentName,
            'DocumentType' => $documentType,
            'DocumentSource' => 'Moodle',
        ];

        $json = json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $embedding = $this->getEmbeddingData($json);

        return [
            'document_id' => $documentId,
            'textdata' => $json,
            'embedding' => PgVector::toLiteral($embedding),
            'pagenumber' => 1,
            'createdat' => now('UTC'),
            'modifiedat' => now('UTC'),
        ];
    }

    // ── CreateDocumentIngestionStatus (DocumentIngestion.cs:1023) ──────────
    public function createDocumentIngestionStatus(int $chatbotId, string $documentName, int $processingStatus): int
    {
        if ($chatbotId <= 0) {
            throw new RuntimeException('Invalid chatbot id.');
        }
        if (trim($documentName) === '') {
            throw new RuntimeException('Document name is required.');
        }

        $entity = DocumentIngestionStatus::create([
            'companychatbot_id' => $chatbotId,
            'documentname' => $documentName,
            'documentstatus' => $processingStatus,
            'createdat' => now('UTC'),
        ]);

        return (int) $entity->documentingestionstatusid;
    }

    // ── UpdateDocumentIngestionStatus (DocumentIngestion.cs:1055) ──────────
    public function updateDocumentIngestionStatus(int $statusId, int $processingStatus): void
    {
        $row = DocumentIngestionStatus::where('documentingestionstatusid', $statusId)->first();
        if ($row !== null) {
            $row->documentstatus = $processingStatus;
            $row->save();
        }
    }

    // ── GetGreetingQuestionsList (DocumentIngestion.cs:1075) ───────────────
    /** @return array<int,string> */
    public function getGreetingQuestionsList(): array
    {
        return [
            'Hi', 'Hii', 'Hiii', 'Hi!', 'Hi!!', 'Hi there', 'Hi buddy', 'Hi bot', 'Hi AI', 'Hi assistant', 'Hi chatbot',
            'Hello', 'Hello!', 'Hello!!', 'Hello there', 'Hello buddy', 'Hello bot', 'Hello AI', 'Hello assistant', 'Hello chatbot',
            'Hey', 'Hey!', 'Hey!!', 'Hey there', 'Hey buddy', 'Hey bot', 'Hey AI', 'Hey assistant', 'Hey chatbot',
            'Good morning', 'Good afternoon', 'Good evening', 'Good day',
            'Greetings', 'Hola', 'Yo', 'Hlo', 'Hy', 'Hye',
            'Nice to meet you', 'Nice meeting you', 'Pleasure to meet you',
            'Long time no see', 'Good to see you', 'Good to talk to you',
        ];
    }

    // ── CreateGreetingQuestions (DocumentIngestion.cs:1144) ────────────────
    public function createGreetingQuestions(): void
    {
        $now = now('UTC');
        $rows = [];

        foreach ($this->getGreetingQuestionsList() as $greeting) {
            $embedding = $this->getEmbeddingData($greeting);
            $rows[] = [
                'textdata' => $greeting,
                'embedding' => PgVector::toLiteral($embedding),
                'createdat' => $now,
                'modifiedat' => $now,
            ];
        }

        DB::transaction(function () use ($rows) {
            foreach ($rows as $r) {
                DB::insert(
                    'INSERT INTO greetingsquestion (textdata, embeddeddata, createdat, modifiedat)
                     VALUES (?, ?::vector, ?, ?)',
                    [$r['textdata'], $r['embedding'], $r['createdat'], $r['modifiedat']]
                );
            }
        });
    }

    // ── Persistence helper: insert chunk rows with the pgvector column ─────
    /** @param array<int,array<string,mixed>> $chunks */
    private function insertChunks(array $chunks): void
    {
        foreach ($chunks as $c) {
            if ($c['embedding'] === null) {
                DB::insert(
                    'INSERT INTO documentchunkdetails (document_id, textdata, pagenumber, createdat, modifiedat)
                     VALUES (?, ?, ?, ?, ?)',
                    [$c['document_id'], $c['textdata'] ?? null, $c['pagenumber'], $c['createdat'], $c['modifiedat']]
                );
            } else {
                DB::insert(
                    'INSERT INTO documentchunkdetails (document_id, textdata, embeddedchunkdata, pagenumber, createdat, modifiedat)
                     VALUES (?, ?, ?::vector, ?, ?, ?)',
                    [$c['document_id'], $c['textdata'] ?? null, $c['embedding'], $c['pagenumber'], $c['createdat'], $c['modifiedat']]
                );
            }
        }
    }

    // ── ZIP/OOXML helpers ──────────────────────────────────────────────────
    private function readZipEntry(string $zipPath, string $entry): ?string
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return null;
        }
        $contents = $zip->getFromName($entry);
        $zip->close();

        return $contents === false ? null : $contents;
    }

    /**
     * Yield raw bytes of each embedded image under word/media/ (the DOCX ImageParts).
     *
     * @return \Generator<int,string>
     */
    private function docxImageEntries(string $docxPath): \Generator
    {
        $zip = new ZipArchive();
        if ($zip->open($docxPath) !== true) {
            return;
        }
        try {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if ($name !== false && str_starts_with($name, 'word/media/')) {
                    $bytes = $zip->getFromIndex($i);
                    if ($bytes !== false && $bytes !== '') {
                        yield $bytes;
                    }
                }
            }
        } finally {
            $zip->close();
        }
    }
}
