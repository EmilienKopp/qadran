<?php

namespace App\Providers;

use App\Services\WorkOSService;
use Illuminate\Support\ServiceProvider;
use WorkOS\UserManagement;
use WorkOS\WorkOS;

class WorkOSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        app()->singleton(UserManagement::class, function ($app) {
            return new UserManagement;
        });

        app()->singleton(WorkOSService::class, function ($app) {
            return new WorkOSService(
                usersApi: $app->make(UserManagement::class),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        WorkOS::setApiKey(config('workos.api_key'));
    }
}
