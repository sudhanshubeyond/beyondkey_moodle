# Knowledge Platform — PHP/Laravel port

A faithful 1:1 rewrite of the .NET Core "Knowledge Platform – Re Ranking" RAG
chatbot API in **PHP 8.2 / Laravel 11**. Same endpoints, same request/response
shapes, and it connects to the **same PostgreSQL + pgvector database** as the
.NET app (the schema is managed externally — this app does not migrate domain
tables).

The original .NET solution lives alongside this project and is the parity
reference: `../Knowledge Platform - Re Ranking`.

## What it does

Ingests PDF/DOCX documents → extracts text + image descriptions (Azure Document
Intelligence + GPT-4 Vision) → chunks + embeds into pgvector → answers questions
with hybrid vector + BM25 retrieval, weighted re-ranking, and Azure OpenAI
GPT-4o, with multi-turn memory.

## Requirements

- **PHP 8.2+** with extensions: `pdo_pgsql`, `imagick`, `zip`, `mbstring`, `curl`, `openssl`.
- **Composer**.
- **Ghostscript** on the host (Imagick uses it to rasterize PDF pages for figure extraction).
- Access to the **PostgreSQL + pgvector** database used by the .NET app.
- A **queue worker** process for background document ingestion.

On Windows, [Laragon](https://laragon.org/) bundles PHP + Composer; install the
Imagick extension and Ghostscript separately.

## Setup

```bash
composer install
# .env is pre-filled from the .NET appsettings.json; generate the app key:
php artisan key:generate

# Create ONLY the Laravel queue infra tables (jobs, job_batches, failed_jobs)
# in the shared database. Domain tables already exist and are left untouched.
php artisan migrate

# Serve the API
php artisan serve            # http://localhost:8000

# In a second terminal — REQUIRED for document ingestion:
php artisan queue:work
```

> ⚠️ **Rotate the secrets.** `.env` was seeded verbatim from the .NET
> `appsettings.json` (DB password, Azure OpenAI / Document Intelligence /
> GPT-4V keys, and the `AUTH_API_KEY`). These were already exposed in the .NET
> repo — rotate them and update `.env`.

## Endpoints

All routes require the `X-Api-Key` header (= `AUTH_API_KEY`). Paths mirror the
.NET `api/[controller]/[action]` convention.

| Method | Route | Body / Query |
|---|---|---|
| POST | `/api/ChatbotQNA/GetQnAResponse` | JSON `{ "Question": "...", "SessionId": "..." }` |
| GET  | `/api/ChatbotQNA/DeleteQNAHistory` | `?sessionId=...` |
| POST | `/api/DocumentIngestion/TriggerDocumentIngestion` | multipart: `Documents[]`, `ChatbotID`, `UserEmail`, `DocumentURL` |
| POST | `/api/DocumentIngestion/CreateGreetingQuestionsEmbeddings` | — |
| POST | `/api/Chatbot/CreateChatbot` | JSON `CompanyChatbotViewModel` |
| GET  | `/api/Chatbot/GetChatbot` | — |

Example:

```bash
curl -X POST http://localhost:8000/api/ChatbotQNA/GetQnAResponse \
  -H "X-Api-Key: $AUTH_API_KEY" -H "Content-Type: application/json" \
  -d '{"Question":"What does the flexible life plan cover?","SessionId":"demo-1"}'
```

## Verification (parity with .NET)

1. **DB connectivity** — `php artisan tinker` then:
   `DB::select("SELECT count(*) FROM documentchunkdetails");`
   and a vector op: `DB::select("SELECT (embeddedchunkdata <=> embeddedchunkdata) d FROM documentchunkdetails LIMIT 1");`
2. **Auth** — a request without/with a wrong `X-Api-Key` returns `401`; correct key passes.
3. **QnA parity** — point this app and the .NET app at the same DB and POST
   identical `GetQnAResponse` requests (greeting, simple factual, composite,
   and a multi-turn follow-up reusing `SessionId`); compare the JSON envelopes
   and the `queryresponse` / `qnasummary` rows written.
4. **Ingestion** — with `queue:work` running, `TriggerDocumentIngestion` a PDF
   and a DOCX; watch `documentingestionstatus` move Processing → Processed and
   verify the new `documentchunkdetails` (1536-dim embeddings, non-empty `tsv`).
5. **Greetings** — `CreateGreetingQuestionsEmbeddings` seeds ~46 `greetingsquestion` rows.

## Source-map (.NET → PHP)

| .NET | PHP |
|---|---|
| `Filters/ApiKeyAuthenticationFilter.cs` | `app/Http/Middleware/ApiKeyAuth.php` |
| `BAL/Classes/ChatbotQNA.cs` | `app/Services/ChatbotQnaService.php` |
| `BAL/Classes/DocumentIngestion.cs` | `app/Services/DocumentIngestionService.php` + `app/Jobs/IngestDocumentsJob.php` |
| `BAL/Classes/CompanyChatbotDetails.cs` | `app/Services/CompanyChatbotService.php` |
| `BAL/Classes/ErrorLogging.cs` | `app/Services/ErrorLogger.php` |
| Azure OpenAI SDK / Semantic Kernel | `app/Services/AzureOpenAiService.php` |
| Azure Document Intelligence SDK | `app/Services/DocumentIntelligenceService.php` |
| GPT-4V raw HTTP | `app/Services/VisionOcrService.php` |
| `Controllers/*.cs` | `app/Http/Controllers/*.php` |
| `DAL/Entities/*` | `app/Models/*` |
| `appsettings.json` | `.env` + `config/knowledge.php` + `config/prompts.php` |

## Parity notes (mechanism differs, behavior preserved)

- **PDF figure rendering** uses Imagick + Ghostscript (0-based page index, like
  PdfiumViewer) instead of PdfiumViewer/ImageSharp — the base64 sent to GPT-4V
  is not byte-identical, but the extracted description is equivalent.
- **DOCX parsing** reads the OOXML directly (`ZipArchive` + `DOMDocument`),
  reproducing the `"Page N:"` pagination heuristic and image extraction.
- **Background ingestion** is a Laravel queued job (needs `queue:work`) instead
  of the in-process `Task.Run`.
- **Document Intelligence** is called over REST with manual `Operation-Location`
  polling (the SDK's `WaitUntil.Completed`).
- **Response JSON** matches ASP.NET Core's camelCase policy
  (`status`, `message`, `data`, `queryDocumentDetails`, `documentURL`, …).
