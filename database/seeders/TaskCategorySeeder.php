<?php

namespace Database\Seeders;

use App\Models\TaskCategory;
use Illuminate\Database\Seeder;

class TaskCategorySeeder extends Seeder
{
    protected array $categories = [
        // Development
        [
            'name' => 'Feature Development',
            'description' => 'New feature implementation',
            'aliases' => ['feature', 'feat', 'enhancement'],
        ],
        [
            'name' => 'Bug Fix',
            'description' => 'Resolution of bugs and issues',
            'aliases' => ['bug', 'fix', 'defect', 'issue'],
        ],
        [
            'name' => 'Refactoring',
            'description' => 'Code improvement without changing behavior',
            'aliases' => ['refactor', 'cleanup', 'restructure'],
        ],
        [
            'name' => 'Technical Debt',
            'description' => 'Addressing technical debt and legacy code',
            'aliases' => ['tech-debt', 'debt', 'legacy'],
        ],
        [
            'name' => 'Performance',
            'description' => 'Performance optimization and improvements',
            'aliases' => ['optimization', 'perf', 'speed'],
        ],

        // Testing & QA
        [
            'name' => 'Unit Testing',
            'description' => 'Writing and maintaining unit tests',
            'aliases' => ['unit-test', 'test', 'testing'],
        ],
        [
            'name' => 'Integration Testing',
            'description' => 'Integration test development and execution',
            'aliases' => ['integration', 'integration-test'],
        ],
        [
            'name' => 'QA Testing',
            'description' => 'Manual testing and quality assurance',
            'aliases' => ['qa', 'quality', 'manual-test'],
        ],

        // Infrastructure
        [
            'name' => 'DevOps',
            'description' => 'CI/CD and infrastructure tasks',
            'aliases' => ['ci-cd', 'pipeline', 'infrastructure'],
        ],
        [
            'name' => 'Security',
            'description' => 'Security-related tasks and fixes',
            'aliases' => ['sec', 'security-fix', 'vulnerability'],
        ],
        [
            'name' => 'Database',
            'description' => 'Database-related tasks and optimization',
            'aliases' => ['db', 'sql', 'migration'],
        ],

        // Documentation
        [
            'name' => 'Documentation',
            'description' => 'Code and technical documentation',
            'aliases' => ['docs', 'readme', 'wiki'],
        ],
        [
            'name' => 'API Documentation',
            'description' => 'API specification and documentation',
            'aliases' => ['api-docs', 'swagger', 'openapi'],
        ],

        // Design & UI/UX
        [
            'name' => 'UI Implementation',
            'description' => 'User interface implementation',
            'aliases' => ['ui', 'frontend', 'interface'],
        ],
        [
            'name' => 'UX Enhancement',
            'description' => 'User experience improvements',
            'aliases' => ['ux', 'usability', 'experience'],
        ],
        [
            'name' => 'Design System',
            'description' => 'Design system and component library',
            'aliases' => ['components', 'design-lib', 'ui-kit'],
        ],

        // Project Management
        [
            'name' => 'Planning',
            'description' => 'Project planning and roadmap',
            'aliases' => ['plan', 'roadmap', 'strategy'],
        ],
        [
            'name' => 'Research',
            'description' => 'Research and feasibility studies',
            'aliases' => ['research', 'investigation', 'study'],
        ],
        [
            'name' => 'Requirements',
            'description' => 'Requirements gathering and analysis',
            'aliases' => ['reqs', 'specs', 'requirements'],
        ],

        // Support
        [
            'name' => 'Customer Support',
            'description' => 'Customer-facing support tasks',
            'aliases' => ['support', 'customer', 'help'],
        ],
        [
            'name' => 'Maintenance',
            'description' => 'Regular system maintenance',
            'aliases' => ['maintain', 'upkeep', 'routine'],
        ],
        [
            'name' => 'Training',
            'description' => 'Training and knowledge transfer',
            'aliases' => ['train', 'learning', 'knowledge'],
        ],

        // Business
        [
            'name' => 'Business Analysis',
            'description' => 'Business analysis and reporting',
            'aliases' => ['ba', 'analysis', 'reporting'],
        ],
        [
            'name' => 'Client Communication',
            'description' => 'Client meetings and communications',
            'aliases' => ['client', 'meeting', 'communication'],
        ],
        [
            'name' => 'Administrative',
            'description' => 'Administrative and operational tasks',
            'aliases' => ['admin', 'ops', 'operation'],
        ],
    ];

    public function run(): void
    {
        foreach ($this->categories as $category) {
            $aliases = $category['aliases'] ?? [];
            unset($category['aliases']);

            $taskCategory = TaskCategory::create($category);

            // Create aliases for the category
            foreach ($aliases as $alias) {
                $taskCategory->aliases()->create([
                    'alias' => $alias,
                    'organization_id' => null, // System-wide alias
                    'user_id' => null,
                ]);
            }
        }
    }
}
