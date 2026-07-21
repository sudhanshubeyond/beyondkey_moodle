<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * The ported services (AzureOpenAiService, ChatbotQnaService, ErrorLogger,
     * DocumentIngestionService, etc.) are plain classes with constructor
     * dependencies that Laravel's container resolves automatically, so no
     * explicit bindings are required here (mirrors the AddScoped/AddSingleton
     * registrations in the .NET Program.cs).
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
