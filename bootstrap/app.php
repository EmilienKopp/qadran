<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens([
            'webhooks/*',
            'oauth/token',
        ]);
        $middleware->web(prepend: [
            \App\Http\Middleware\AddHostToContext::class,
            \App\Http\Middleware\PreventPlusAddressing::class,
            // \App\Http\Middleware\ForceWebContext::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\EnsureAvailableTenantsContext::class, // do not put AFTER HandleInertiaRequests
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \App\Http\Middleware\EnsureCLIOAuthClient::class, // Will ultimately be removed and part of User onboarding process!
        ]);
        $middleware->statefulApi();

        // Register custom middleware aliases
        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
