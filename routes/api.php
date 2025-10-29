<?php

// use App\Http\Controllers\API\GitReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/git-report', [GitReportController::class, 'generate']);
});

Route::get('/artisan', [\App\Http\Controllers\Api\ArtisanController::class, 'run'])
    ->name('api.artisan')
    ->middleware(['auth:sanctum','api']);

// Webhook endpoint for n8n to post Jira known issues (public, no auth)
Route::post('/webhooks/jira/known-issues', [\App\Http\Controllers\KnownIssueWebhookController::class, 'store'])
    ->name('api.webhooks.jira.known-issues');

// Route::post('/git-report', [GitReportController::class, 'generate']);