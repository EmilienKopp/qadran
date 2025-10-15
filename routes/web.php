<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Application;
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

$APP_HOST = Uri::of(env('APP_URL'))->host();

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

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
