<?php

use App\Http\Middleware\ApiKeyAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // API routes are stateless; register the X-Api-Key guard as a named alias
        // so controllers/routes can opt in (mirrors the .NET [ServiceFilter(ApiKeyAuthenticationFilter)]).
        $middleware->alias([
            'apikey' => ApiKeyAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
