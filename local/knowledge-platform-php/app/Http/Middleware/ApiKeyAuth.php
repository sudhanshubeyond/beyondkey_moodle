<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Port of KnowledgePlatform/Filters/ApiKeyAuthenticationFilter.cs.
 *
 * Compares the request "X-Api-Key" header against the configured key and
 * returns HTTP 401 on mismatch (same behavior as the .NET IAuthorizationFilter).
 */
class ApiKeyAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $validApiKey = config('knowledge.auth_api_key');
        $apiKey = $request->header('X-Api-Key');

        if ($apiKey !== $validApiKey) {
            return response('', 401);
        }

        return $next($request);
    }
}
