<?php

namespace App\Providers;

use App\Services\AIService;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    $this->app->singleton(AIService::class, function ($app) {
      return new AIService();
    });
  }
}