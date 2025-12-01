<?php

return [
    'roles' => [
        'freelancer' => [
            [
                'key' => 'portfolio_url',
                'label' => 'Portfolio URL',
                'type' => 'text',
                'required' => false,
                'rule' => 'url',
            ],
            [
                'key' => 'weekly_available_hours',
                'label' => 'Weekly Available Hours',
                'type' => 'number',
                'required' => false,
                'rule' => 'integer',
            ],
        ],
        'employer' => [
            [
                'key' => 'website_url',
                'label' => 'Website URL',
                'type' => 'text',
                'required' => false,
                'rule' => 'url',
            ],
            [
                'key' => 'hiring_preferences',
                'label' => 'Hiring Preferences',
                'type' => 'textarea',
                'required' => false,
                'rule' => 'string|max:1000',
            ],
        ],

        'admin' => [
            [
                'key' => 'notes',
                'label' => 'Admin Notes',
                'type' => 'textarea',
                'required' => false,
                'rule' => 'string|max:2000',
            ],
        ],
    ],
];
