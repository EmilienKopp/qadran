<?php

use App\DataAccess\Facades\Tenant;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Support\InstanceUrl;
use App\Support\RequestContextResolver;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClockEntryController;
use App\Http\Controllers\GitReportController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\GitHubOAuthController;
use App\Services\GitHubService;
use App\Http\Controllers\RateController;
use App\DTOs\GitLogRequest;
use Native\Desktop\Facades\Settings;

$APP_HOST = Uri::of(env('APP_URL'))->host();

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

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
    if(RequestContextResolver::isDesktop()) {
        Settings::set('instance_url', $instanceUrl );
        Settings::set('tenant', $tenant );
    }
    Config::set('services.api.base_url', $instanceUrl);
    $tenant->makeCurrent();
    return Inertia::render('TenantWelcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'domain' => $tenant->domain,
    ]);
})->name('find-tenant');

if (app()->isProduction()) {
    Route::domain('{account}.' . $APP_HOST)->group(function () {
        require __DIR__ . '/tenant.php';
    });
} else {
    Route::prefix('')->group(function () {
        require __DIR__ . '/tenant.php';
    });
}




require __DIR__ . '/auth.php';
