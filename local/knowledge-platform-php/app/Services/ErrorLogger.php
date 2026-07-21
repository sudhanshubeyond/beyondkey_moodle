<?php

namespace App\Services;

use App\Models\ErrorLog;
use Throwable;

/**
 * Port of BAL/Classes/ErrorLogging.cs.
 *
 * Writes exceptions to the `errorlog` table. The .NET signature used
 * [CallerMemberName] for the method name; PHP has no direct equivalent, so
 * callers pass it explicitly (default empty), matching how it is composed
 * into `source` as "{source} - {methodName}".
 */
class ErrorLogger
{
    public function logError(
        Throwable $ex,
        string $source,
        ?string $tenantURL = null,
        ?string $additionalInfo = null,
        string $methodName = '',
        string $userEmail = ''
    ): void {
        ErrorLog::create([
            'errormessage' => $ex->getMessage(),
            'stacktrace' => $ex->getTraceAsString(),
            'loglevel' => 'Error',
            'source' => "{$source} - {$methodName}",
            'createdat' => now('UTC'),
            'additionalinfo' => $additionalInfo,
            'createdby' => $userEmail,
        ]);
    }
}
