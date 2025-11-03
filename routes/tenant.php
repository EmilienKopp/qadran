<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClockEntryController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\GitHubOAuthController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\AudioController;
use App\Support\RequestContextResolver;
use App\Models\Landlord\Tenant;

$APP_HOST = Uri::of(env('APP_URL'))->host();

// Set default guard for tenant routes
config(['auth.defaults.guard' => 'tenant']);

// Debug: Check tenant and database connection
\Log::debug('Tenant routes loading', [
    'current_tenant' => Tenant::current()?->id,
    'tenant_database' => Tenant::current()?->database,
    'tenant_connection_database' => config('database.connections.tenant.database'),
    'auth_guard' => config('auth.defaults.guard'),
]);



Route::get('/', function () {
  return Inertia::render('TenantWelcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'domain' => Tenant::current()?->domain,
  ]);
})->name('tenant.welcome');

Route::get('/dashboard', function () {
  $user = auth('tenant')->user();
  $user = app(UserRepositoryInterface::class)->find($user->id, ['projects', 'todaysEntries']);
  return Inertia::render('Dashboard', compact('user'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

  Route::get('/terminal', function () {
    return Inertia::render('Terminal');
  })->name('terminal');

  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  Route::group(['prefix' => 'clock-entry'], function () {
    Route::post('/store', [ClockEntryController::class, 'store'])->name('clock-entry.store');
  });

  Route::group(['prefix' => 'audio'], function () {
    Route::post('/transcribe', [AudioController::class, 'transcribe'])->name('audio.transcribe');
    Route::post('/command', [AudioController::class, 'command'])->name('audio.command');
  });

  Route::group(['prefix' => 'projects'], function () {
    Route::get('/', [ProjectController::class, 'index'])->name('project.index');
    Route::get('/create', [ProjectController::class, 'create'])->name('project.create');
    Route::post('/store', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('project.show');
    Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('project.edit');
    Route::patch('/{project}', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('project.destroy');
  });

  Route::group(['prefix' => 'organizations'], function () {
    Route::get('/', [OrganizationController::class, 'index'])->name('organization.index');
    Route::get('/create', [OrganizationController::class, 'create'])->name('organization.create');
    Route::post('/store', [OrganizationController::class, 'store'])->name('organization.store');
    Route::get('/{organization}', [OrganizationController::class, 'show'])->name('organization.show');
    Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])->name('organization.edit');
    Route::patch('/{organization}', [OrganizationController::class, 'update'])->name('organization.update');
    Route::delete('/{organization}', [OrganizationController::class, 'destroy'])->name('organization.destroy');
  });

  Route::group(['prefix' => 'clock-entries'], function () {
    Route::get('/', [ClockEntryController::class, 'index'])->name('clock-entry.index');
    Route::get('/{clockEntry}', [ClockEntryController::class, 'show'])->name('clock-entry.show');
    Route::get('/{clockEntry}/edit', [ClockEntryController::class, 'edit'])->name('clock-entry.edit');
    Route::patch('/{clockEntry}', [ClockEntryController::class, 'update'])->name('clock-entry.update');
    Route::delete('/{clockEntry}', [ClockEntryController::class, 'destroy'])->name('clock-entry.destroy');
  });

  Route::group(['prefix' => 'rates'], function () {
    Route::get('/', [RateController::class, 'index'])->name('rate.index');
    Route::get('/create', [RateController::class, 'create'])->name('rate.create');
    Route::post('/store', [RateController::class, 'store'])->name('rate.store');
    Route::get('/{rate}', [RateController::class, 'show'])->name('rate.show');
    Route::get('/{rate}/edit', [RateController::class, 'edit'])->name('rate.edit');
    Route::patch('/{rate}', [RateController::class, 'update'])->name('rate.update');
    Route::delete('/{rate}', [RateController::class, 'destroy'])->name('rate.destroy');
  });


  Route::group(['prefix' => 'report'], function () {
    Route::get('/', [ReportController::class, 'index'])->name('report.index');
    Route::get('/create', [ReportController::class, 'create'])->name('report.create');
    Route::post('/store', [ReportController::class, 'store'])->name('report.store');
    Route::post('/generate', [ReportController::class, 'generate'])->name('report.generate');
    Route::post('/logs', [ReportController::class, 'fetchCommits'])->name('report.fetch-commits');
    Route::get('/{report}', [ReportController::class, 'show'])->name('report.show');
    Route::get('/{report}/edit', [ReportController::class, 'edit'])->name('report.edit');
    Route::patch('/{report}', [ReportController::class, 'update'])->name('report.update');
    Route::delete('/{report}', [ReportController::class, 'destroy'])->name('report.destroy');
  });

  Route::group(['prefix' => 'settings'], function () {
    Route::get('/integrations', function () {
      $connected = Auth::user()->hasGitHubConnection();
      $tokenIsInvalid = $connected ? Auth::user()->gitHubConnection->isTokenExpired() : false;
      $githubStatus = [
        'connected' => $connected,
        'token_expired' => $connected ? Auth::user()->gitHubConnection->isTokenExpired() : false,
        'status' => $tokenIsInvalid ? 'invalid' : ($connected ? 'connected' : 'disconnected'),
        'username' => $connected ? Auth::user()->gitHubConnection->username : '',
        'connected_at' => $connected ? Auth::user()->gitHubConnection->created_at->toDateTimeString() : null,
      ];
      return Inertia::render('Settings/Integrations', compact('githubStatus'));
    })->name('settings.integrations');
  });

  Route::middleware('auth')->group(function () {
    // GitHub OAuth routes
    Route::get('/settings/github/connect', [GitHubOAuthController::class, 'redirect'])
      ->name('github.connect');
    Route::get('/auth/github/callback', [GitHubOAuthController::class, 'callback'])
      ->name('github.callback');
    Route::post('/settings/github/confirm-replace', [GitHubOAuthController::class, 'confirmReplace'])
      ->name('github.confirm-replace');
    Route::delete('/settings/github/disconnect', [GitHubOAuthController::class, 'disconnect'])
      ->name('github.disconnect');

    // API endpoint for status
    Route::get('/api/github/status', [GitHubOAuthController::class, 'status'])
      ->name('github.status');
  });

});

