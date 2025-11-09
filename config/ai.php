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
    | AI Action Driver
    |--------------------------------------------------------------------------
    |
    | The default driver for AI actions. Can be 'prism' or 'n8n'.
    | Different operations may use different drivers based on their needs.
    |
    */
    'driver' => env('AI_DRIVER', 'prism'),
];
