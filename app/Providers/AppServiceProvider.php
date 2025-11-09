<?php

namespace App\Providers;

use App\Models\Landlord\Tenant;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Laravel\Pennant\Feature;
use Native\Desktop\App;
use WorkOS\UserManagement;
use WorkOS\WorkOS;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;

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
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        // Use file-based sessions for desktop mode (Redis not available)
        if (config('nativephp-internal.running', false)) {
            config(['session.driver' => 'file']);
            config(['session.domain' => null]);
            config(['session.secure' => false]);
            config(['session.same_site' => null]); // Disable SameSite for desktop to allow OAuth redirects
            config(['session.http_only' => true]);

            // Register custom user provider for desktop mode
            Auth::provider('remote', function ($app, array $config) {
                return new \App\Auth\RemoteUserProvider();
            });

            // Use remote provider for tenant guard
            config(['auth.providers.tenant_users.driver' => 'remote']);
        }
    }
}
