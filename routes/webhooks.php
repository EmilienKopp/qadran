<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{KnownIssueWebhookController};

// Webhook endpoint for n8n to post Jira known issues (public, no auth)
Route::post('/webhooks/jira/known-issues', [KnownIssueWebhookController::class, 'store'])
  ->name('api.webhooks.jira.known-issues');