<?php


return [

    'associations' => [
      'qadranio' => config('plans.secret'),
      'devdan' => config('plans.secret'),
      'demo' => config('plans.secret'),
    ],

    'secret' => [
        'name' => 'Secret',
        'features' => [
            'voice_assistant' => 'secret',
        ],
    ],

    'free' => [
        'name' => 'Free',
        'features' => [
            'voice_assistant' => 'free',
        ],
    ],
    'standard' => [
        'name' => 'Standard',
        'features' => [
            'voice_assistant' => 'standard',
        ],
    ],
    'premium' => [
        'name' => 'Premium',
        'features' => [
            'voice_assistant' => 'premium',
        ],
    ],
  ];