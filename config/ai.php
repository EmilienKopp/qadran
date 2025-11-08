<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Text Generation Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for text generation AI services (e.g., for git report generation)
    |
    */
    'text_generation' => [
        'provider' => env('AI_TEXT_PROVIDER', 'gemini'),
        'model' => env('AI_TEXT_MODEL', 'gemini-2.0-flash'),
    ],

    /*
    |--------------------------------------------------------------------------
    | N8n Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for n8n webhook endpoints for offloaded AI processing
    |
    */
    'n8n' => [
        'webhook_url' => env('AI_N8N_WEBHOOK_URL', 'http://host.docker.internal:5678/webhook/4ffc04bd-d7c9-46b7-be49-1245185ae742'),
        'assistant_webhook_url' => env('AI_N8N_ASSISTANT_WEBHOOK_URL'),
    ],


    /*
    |--------------------------------------------------------------------------
    | AI Action Driver
    |--------------------------------------------------------------------------
    |
    | The default driver for AI actions. Can be 'prism' or 'n8n'.
    | Different operations may use different drivers based on their needs.
    |
    */
    'driver' => env('AI_DRIVER', 'prism'),
];
