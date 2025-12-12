<?php

use App\DataAccess\Facades\Tenant;
use App\Http\Controllers\KnownIssuesController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Support\InstanceUrl;
use App\Support\RequestContextResolver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Uri;
use Inertia\Inertia;
use Native\Desktop\Facades\Settings;

$APP_HOST = Uri::of(env('APP_URL'))->host();

// Public known issues page (landlord-scoped, no auth required)
Route::get('/known-issues', [KnownIssuesController::class, 'index'])
    ->name('known-issues.index');

// Public privacy policy page (landlord-scoped, no auth required)
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])
    ->name('privacy-policy.index');

Route::get('/', function () {
    return Inertia::render('Landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('root');

Route::post('/find-tenant', function (Illuminate\Http\Request $request) {
    $spaceName = $request->input('space');

    if (!$spaceName) {
        return back()->withErrors(['space' => 'Space name is required']);
    }

    $tenant = Tenant::getByDomainOrHost($spaceName);

    if (!$tenant) {
        return back()->withErrors(['space' => 'Space not found. Please check the name and try again.']);
    }
    $instanceUrl = InstanceUrl::fetch($tenant['host']);
    if (RequestContextResolver::isDesktop()) {
        Settings::set('instance_url', $instanceUrl);
        Settings::set('tenant', $tenant);
    }
    Config::set('services.api.base_url', $instanceUrl);
    $tenant->makeCurrent();

    return Inertia::render('TenantWelcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'domain' => $tenant->domain,
    ]);
})->name('find-tenant');


require __DIR__ . '/auth.php';
require __DIR__ . '/webhooks.php';

// Register Passport OAuth routes BEFORE {account} prefix to prevent route conflicts
Route::middleware(['auth:tenant'])->prefix('oauth')->as('passport.')->group(function () {
    Route::get('/authorize', [\Laravel\Passport\Http\Controllers\AuthorizationController::class, 'authorize'])
        ->name('authorizations.authorize');
    Route::post('/authorize', [\Laravel\Passport\Http\Controllers\ApproveAuthorizationController::class, 'approve'])
        ->name('authorizations.approve');
    Route::delete('/authorize', [\Laravel\Passport\Http\Controllers\DenyAuthorizationController::class, 'deny'])
        ->name('authorizations.deny');
});

Route::prefix('oauth')->as('passport.')->group(function () {
    Route::post('/token', [\Laravel\Passport\Http\Controllers\AccessTokenController::class, 'issueToken'])
        ->name('token');
    Route::post('/token/refresh', [\Laravel\Passport\Http\Controllers\TransientTokenController::class, 'refresh'])
        ->name('token.refresh');
});

if (app()->isProduction()) {
    if (app()->environment('staging')) {
        Route::prefix('{account}')->group(function () {
            require __DIR__ . '/tenant.php';
        });
    } else {
        Route::domain('{account}.' . $APP_HOST)->group(function () {
            require __DIR__ . '/tenant.php';
        });
    }
} else {
    Route::prefix('{account}')->group(function () {
        require __DIR__ . '/tenant.php';
    });
}