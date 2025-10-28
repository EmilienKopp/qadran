<?php

namespace App\Providers;

use App\Support\RequestContextResolver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Native\Desktop\App;
use WorkOS\UserManagement;
use WorkOS\WorkOS;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->singleton(UserManagement::class, function ($app) {
            return new UserManagement();
        });

        // Register multitenancy service provider
        $this->app->register(\Spatie\Multitenancy\MultitenancyServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        WorkOS::setApiKey(config('workos.api_key'));
    }
}
