<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ai' => [
        'key' => env('AI_API_KEY'),
        'base_url' => env('AI_BASE_URL'),
        'version' => env('AI_VERSION', 'v1'),
        'endpoint' => env('AI_ENDPOINT', 'messages'),
    ],

    'api' => [
        'base_url' => env('API_BASE_URL', 'http://localhost:8000'),
    ],

    'workos' => [
        'client_id' => env('WORKOS_CLIENT_ID'),
        'secret' => env('WORKOS_API_KEY'),
        'redirect' => env('WORKOS_REDIRECT_URL'),
        'default_user' => env('WORKOS_DEFAULT_USER'),
    ],

    
    'n8n' => [
        'webhook_url' => env('AI_N8N_WEBHOOK_URL', 'http://host.docker.internal:5678/webhook/4ffc04bd-d7c9-46b7-be49-1245185ae742'),
        'assistant_webhook_url' => env('AI_N8N_ASSISTANT_WEBHOOK_URL'),
        'base_url' => env('N8N_BASE_URL'),
        'secret' => env('N8N_SECRET'),
        'api_url' => env('N8N_API_URL'),
        'api_key' => env('N8N_API_KEY'),
        'ai_credential_id' => env('N8N_AI_CREDENTIAL_ID'),
        'ai_credential_name' => env('N8N_AI_CREDENTIAL_NAME'),
    ],

];
