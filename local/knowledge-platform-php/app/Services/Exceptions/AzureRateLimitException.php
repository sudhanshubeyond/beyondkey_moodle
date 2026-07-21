<?php

namespace App\Services\Exceptions;

use RuntimeException;

/**
 * Thrown when an Azure endpoint returns HTTP 429 (Too Many Requests).
 *
 * Mirrors the .NET code paths that catch RequestFailedException/HttpRequestException
 * with Status == 429 to drive exponential-backoff retry loops.
 */
class AzureRateLimitException extends RuntimeException
{
}
