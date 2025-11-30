<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class GitHubServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('github', function ($app) use ($socialite) {
            $config = [
                'client_id' => config('services.github.client_id'),
                'client_secret' => config('services.github.client_secret'),
                'redirect' => config('services.github.redirect'),
            ];

            return $socialite->buildProvider(
                \Laravel\Socialite\Two\GithubProvider::class,
                $config
            );
        });
    }
}
