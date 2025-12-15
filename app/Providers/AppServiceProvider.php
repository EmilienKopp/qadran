<?php

namespace App\Providers;

use App\Models\Landlord\Tenant;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Spatie\Multitenancy\Contracts\IsTenant;
use WorkOS\Resource\OrganizationMembership;
use WorkOS\UserManagement;
use WorkOS\WorkOS;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register multitenancy service provider
        $this->app->register(\Spatie\Multitenancy\MultitenancyServiceProvider::class);

        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Route::bind('account', function ($value) {
            // Tenant is already found by TenantFinder, just return it
            return Tenant::current() ?? Tenant::where('host', $value)->firstOrFail();
        });

        // Use file-based sessions for desktop mode (Redis not available)
        if (config('nativephp-internal.running', false)) {
            config(['session.driver' => 'file']);
            config(['session.domain' => null]);
            config(['session.secure' => false]);
            config(['session.same_site' => null]); // Disable SameSite for desktop to allow OAuth redirects
            config(['session.http_only' => true]);

            // Register custom user provider for desktop mode
            Auth::provider('remote', function ($app, array $config) {
                return new \App\Auth\RemoteUserProvider;
            });

            // Use remote provider for tenant guard
            config(['auth.providers.tenant_users.driver' => 'remote']);
        }
    }
}
