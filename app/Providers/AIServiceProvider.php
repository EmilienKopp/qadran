<?php

namespace App\Providers;

use App\Services\AI\Actions\N8nAIAction;
use App\Services\AI\Actions\PrismAIAction;
use App\Services\AIService;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register Prism implementation
        $this->app->bind('prism.ai.action', function ($app) {
            return new PrismAIAction;
        });

        // Register N8n implementation
        $this->app->bind('n8n.ai.action', function ($app) {
            return new N8nAIAction;
        });

        // Register AIService with appropriate implementations
        $this->app->singleton(AIService::class, function ($app) {
            // Text generation uses Prism
            $textAction = $app->make('prism.ai.action');

            // Command processing uses N8n (can be configured)
            $commandAction = $app->make('n8n.ai.action');

            return new AIService($textAction, $commandAction);
        });
    }
}
