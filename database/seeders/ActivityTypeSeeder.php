<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use Illuminate\Database\Seeder;

class ActivityTypeSeeder extends Seeder
{
    protected array $activityTypes = [
        // Development Activities
        [
            'name' => 'Development',
            'description' => 'Core development activities',
            'color' => '#4CAF50',
            'icon' => 'code',
        ],
        [
            'name' => 'Code Review',
            'description' => 'Code review and pull requests',
            'color' => '#2196F3',
            'icon' => 'git-pull-request',
        ],
        [
            'name' => 'Testing',
            'description' => 'Testing and quality assurance',
            'color' => '#9C27B0',
            'icon' => 'bug',
        ],
        [
            'name' => 'Architecture',
            'description' => 'System design and architecture',
            'color' => '#FF5722',
            'icon' => 'building',
        ],
        [
            'name' => 'DevOps',
            'description' => 'Deployment and operations',
            'color' => '#607D8B',
            'icon' => 'server',
        ],

        // Design Activities
        [
            'name' => 'UI Design',
            'description' => 'User interface design',
            'color' => '#E91E63',
            'icon' => 'layout',
        ],
        [
            'name' => 'UX Design',
            'description' => 'User experience design',
            'color' => '#F44336',
            'icon' => 'users',
        ],

        // Documentation & Research
        [
            'name' => 'Documentation',
            'description' => 'Technical documentation',
            'color' => '#795548',
            'icon' => 'file-text',
        ],
        [
            'name' => 'Research',
            'description' => 'Research and development',
            'color' => '#673AB7',
            'icon' => 'search',
        ],

        // Communication & Management
        [
            'name' => 'Client Meeting',
            'description' => 'Client communications and meetings',
            'color' => '#3F51B5',
            'icon' => 'users',
        ],
        [
            'name' => 'Internal Meeting',
            'description' => 'Team and internal meetings',
            'color' => '#2196F3',
            'icon' => 'users',
        ],
        [
            'name' => 'Project Management',
            'description' => 'Project planning and management',
            'color' => '#009688',
            'icon' => 'trello',
        ],

        // Support & Maintenance
        [
            'name' => 'Bug Fixing',
            'description' => 'Bug fixes and maintenance',
            'color' => '#F44336',
            'icon' => 'tool',
        ],
        [
            'name' => 'Customer Support',
            'description' => 'Customer support and assistance',
            'color' => '#00BCD4',
            'icon' => 'help-circle',
        ],
        [
            'name' => 'Technical Support',
            'description' => 'Technical support and troubleshooting',
            'color' => '#FF9800',
            'icon' => 'settings',
        ],

        // Learning & Growth
        [
            'name' => 'Learning',
            'description' => 'Personal development and learning',
            'color' => '#8BC34A',
            'icon' => 'book',
        ],
        [
            'name' => 'Mentoring',
            'description' => 'Teaching and mentoring others',
            'color' => '#CDDC39',
            'icon' => 'users',
        ],

        // Business Development
        [
            'name' => 'Sales',
            'description' => 'Sales and business development',
            'color' => '#FFC107',
            'icon' => 'trending-up',
        ],
        [
            'name' => 'Marketing',
            'description' => 'Marketing and promotion',
            'color' => '#FF5722',
            'icon' => 'trending-up',
        ],
        [
            'name' => 'Administration',
            'description' => 'Administrative tasks',
            'color' => '#9E9E9E',
            'icon' => 'file',
        ],
    ];

    public function run(): void
    {
        foreach ($this->activityTypes as $type) {
            $activityType = ActivityType::create($type);
        }
    }
}
