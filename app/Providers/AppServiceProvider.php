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

        // Register repositories
        $this->registerRepositories();
    }

    /**
     * Register repository bindings based on context (desktop vs web)
     */
    private function registerRepositories(): void
    {
        $isDesktop = RequestContextResolver::isDesktop();

        \Log::debug('Repository registration', [
            'isDesktop' => $isDesktop,
            'context' => RequestContextResolver::getExecutionContext()->value ?? 'unknown',
            'sapi' => php_sapi_name(),
        ]);

        // User Repository
        $this->app->bind(
            \App\Repositories\UserRepositoryInterface::class,
            function ($app) use ($isDesktop) {
                if ($isDesktop) {
                    return new \App\Repositories\Remote\RemoteUserRepository(
                        new \GuzzleHttp\Client(),
                        config('app.api_url')
                    );
                }
                return new \App\Repositories\Local\LocalUserRepository();
            }
        );

        // Organization Repository
        $this->app->bind(
            \App\Repositories\OrganizationRepositoryInterface::class,
            function ($app) use ($isDesktop) {
                if ($isDesktop) {
                    return new \App\Repositories\Remote\RemoteOrganizationRepository(
                        new \GuzzleHttp\Client(),
                        config('app.api_url')
                    );
                }
                return new \App\Repositories\Local\LocalOrganizationRepository();
            }
        );

        // Project Repository
        $this->app->bind(
            \App\Repositories\ProjectRepositoryInterface::class,
            function ($app) use ($isDesktop) {
                if ($isDesktop) {
                    return new \App\Repositories\Remote\RemoteProjectRepository(
                        new \GuzzleHttp\Client(),
                        config('app.api_url')
                    );
                }
                return new \App\Repositories\Local\LocalProjectRepository();
            }
        );

        // Report Repository
        $this->app->bind(
            \App\Repositories\ReportRepositoryInterface::class,
            function ($app) use ($isDesktop) {
                if ($isDesktop) {
                    return new \App\Repositories\Remote\RemoteReportRepository(
                        new \GuzzleHttp\Client(),
                        config('app.api_url')
                    );
                }
                return new \App\Repositories\Local\LocalReportRepository();
            }
        );

        // Tenant Repository
        $this->app->bind(
            \App\Repositories\TenantRepositoryInterface::class,
            function ($app) use ($isDesktop) {
                if ($isDesktop) {
                    return new \App\Repositories\Remote\RemoteTenantRepository(
                        new \GuzzleHttp\Client(),
                        config('app.api_url')
                    );
                }
                return new \App\Repositories\Local\LocalTenantRepository();
            }
        );

        // ClockEntry Repository
        $this->app->bind(
            \App\Repositories\ClockEntryRepositoryInterface::class,
            function ($app) use ($isDesktop) {
                if ($isDesktop) {
                    return new \App\Repositories\Remote\RemoteClockEntryRepository(
                        new \GuzzleHttp\Client(),
                        config('app.api_url')
                    );
                }
                return new \App\Repositories\Local\LocalClockEntryRepository();
            }
        );

        // Task Repository
        $this->app->bind(
            \App\Repositories\TaskRepositoryInterface::class,
            function ($app) use ($isDesktop) {
                if ($isDesktop) {
                    return new \App\Repositories\Remote\RemoteTaskRepository(
                        new \GuzzleHttp\Client(),
                        config('app.api_url')
                    );
                }
                return new \App\Repositories\Local\LocalTaskRepository();
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        WorkOS::setApiKey(config('workos.api_key'));

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
