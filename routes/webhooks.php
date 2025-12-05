<?php

use App\Http\Controllers\KnownIssueWebhookController;
use Illuminate\Support\Facades\Route;

// Webhook endpoint for n8n to post Jira known issues (public, no auth)
Route::post('/webhooks/jira/known-issues', [KnownIssueWebhookController::class, 'store'])
    ->name('webhooks.jira.known-issues');
