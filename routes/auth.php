<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\GitHubOAuthController;
use App\Http\Controllers\GoogleOAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('welcome/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('welcome/register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Primary authentication: GitHub OAuth
    Route::get('github/login', [GitHubOAuthController::class, 'redirect'])
        ->name('github.login');

    // Primary authentication: Google OAuth
    Route::get('google/login', [GoogleOAuthController::class, 'redirect'])
        ->name('google.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login');

    Route::get('welcome/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('welcome/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('welcome/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('welcome/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

});

// GitHub OAuth callback (handles both login and linking)
Route::get('auth/github/callback', [GitHubOAuthController::class, 'callback'])
    ->name('github.callback');

// Google OAuth callback (handles both login and linking)
Route::get('auth/google/callback', [GoogleOAuthController::class, 'callback'])
    ->name('google.callback');

// WorkOS authentication (commented out - available for rollback)
// Route::get('authenticate', [AuthenticatedSessionController::class, 'authenticate'])
//     ->name('authenticate');

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
