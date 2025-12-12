<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class PassportOauthProvider extends ServiceProvider
{
    public function register(): void
    {
        Passport::ignoreRoutes();
    }

    public function boot(): void
    {
        Passport::authorizationView('auth.oauth.authorize');

        Passport::tokensCan([
            'profile' => 'Access your profile information',
            'activity' => 'Access your activity and time tracking data',
        ]);
    }
}
