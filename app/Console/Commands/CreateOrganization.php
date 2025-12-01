<?php

namespace App\Console\Commands;

use App\Enums\OrganizationType;
use App\Services\OrganizationSetupService;
use Exception;
use Illuminate\Console\Command;
use WorkOS\Organizations;
use WorkOS\WorkOS;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

/**
 * Create Organization Command
 *
 * This command creates a new organization with an admin user and tenant database.
 * It can be used after the initial setup to add additional organizations.
 */
class CreateOrganization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'org:create 
                            {--org-name= : Organization name}
                            {--org-type= : Organization type}
                            {--workos-org-id= : Existing WorkOS organization ID}
                            {--admin-email= : Admin email}
                            {--admin-name= : Admin name}
                            {--admin-password= : Admin password}
                            {--workos-user-id= : Existing WorkOS user ID}
                            {--skip-prompts : Skip interactive prompts and use defaults}
                            {--dry-run : Show what would be done without executing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new organization with admin user and tenant database';

    /**
     * Organization setup service
     */
    protected OrganizationSetupService $setupService;

    /**
     * Create a new command instance.
     */
    public function __construct(OrganizationSetupService $setupService)
    {
        parent::__construct();
        $this->setupService = $setupService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('🏢 Creating new organization...');

        // Check WorkOS configuration
        if (! $this->checkWorkOSConfiguration()) {
            return Command::FAILURE;
        }

        // Dry run mode
        if ($this->option('dry-run')) {
            return $this->dryRun();
        }

        try {
            // Get organization details
            $orgData = $this->getOrganizationDetails();

            // Get user details
            $userData = $this->getUserDetails();

            // Create organization and user
            $result = $this->setupService->createOrganizationAndUser($orgData, $userData);

            info('');
            info('✅ Organization created successfully!');
            info("🏢 Organization: {$result['organization']->name}");
            info("👤 Admin User: {$result['user']->email}");
            info("🏠 Tenant Database: {$result['tenant']->database}");
            info("🌐 Domain: {$result['tenant']->domain}");

            return Command::SUCCESS;

        } catch (Exception $e) {
            error('❌ Organization creation failed: '.$e->getMessage());
            info('Please check the logs and try again.');

            return Command::FAILURE;
        }
    }

    /**
     * Check WorkOS configuration
     */
    private function checkWorkOSConfiguration(): bool
    {
        $apiKey = config('workos.api_key');
        $clientId = config('workos.client_id');

        if (! $apiKey || ! $clientId) {
            error('❌ WorkOS is not properly configured');
            info('Please set WORKOS_API_KEY and WORKOS_CLIENT_ID in your .env file');

            return false;
        }

        try {
            // Test WorkOS connection
            WorkOS::setApiKey($apiKey);
            $organizations = new Organizations;
            $organizations->listOrganizations(limit: 1);
            info('✅ WorkOS connection successful');

            return true;
        } catch (Exception $e) {
            error('❌ WorkOS connection failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Dry run mode
     */
    private function dryRun(): int
    {
        info('🔍 DRY RUN MODE - Showing what would be done:');
        info('');

        $orgData = $this->getOrganizationDetails();
        $userData = $this->getUserDetails();

        info('👥 Organization & User Setup:');
        info("  - Create organization: {$orgData['name']} ({$orgData['type']->value})");
        if (isset($orgData['workos_id'])) {
            info("  - Use existing WorkOS org: {$orgData['workos_id']}");
        } else {
            info('  - Create new WorkOS organization');
        }
        info('  - Create tenant database: qadran_'.\Illuminate\Support\Str::slug($orgData['name'], '_'));
        info("  - Create admin user: {$userData['email']}");
        if (isset($userData['workos_id'])) {
            info("  - Use existing WorkOS user: {$userData['workos_id']}");
        } else {
            info('  - Create new WorkOS user');
        }
        info('  - Assign roles: Admin, Business Owner');
        info('');

        warning('⚠️  This was a dry run. Remove --dry-run to execute the organization creation.');

        return Command::SUCCESS;
    }

    /**
     * Get organization details from user input
     */
    private function getOrganizationDetails(): array
    {
        // Check if running with command line options
        if ($this->option('org-name') && $this->option('org-type')) {
            $orgData = [
                'name' => $this->option('org-name'),
                'type' => OrganizationType::from($this->option('org-type')),
                'workos_choice' => $this->option('workos-org-id') ? 'existing' : 'skip',
                'workos_id' => $this->option('workos-org-id'),
            ];

            return $orgData;
        }

        // Check if skipping prompts
        if ($this->option('skip-prompts')) {
            return [
                'name' => 'New Organization',
                'type' => OrganizationType::Corporation,
                'workos_choice' => 'skip',
                'workos_id' => null,
            ];
        }

        // Interactive mode
        return $this->promptForOrganizationData();
    }

    /**
     * Get user details from user input
     */
    private function getUserDetails(): array
    {
        // Check if running with command line options
        if ($this->option('admin-email') && $this->option('admin-password')) {
            $userData = [
                'name' => $this->option('admin-name') ?? 'Admin User',
                'email' => $this->option('admin-email'),
                'password' => $this->option('admin-password'),
                'workos_choice' => $this->option('workos-user-id') ? 'existing' : 'skip',
                'workos_id' => $this->option('workos-user-id'),
            ];

            return $userData;
        }

        // Check if skipping prompts
        if ($this->option('skip-prompts')) {
            return [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => 'password',
                'workos_choice' => 'skip',
                'workos_id' => null,
            ];
        }

        // Interactive mode
        return $this->promptForUserData();
    }

    /**
     * Prompt for organization data
     */
    private function promptForOrganizationData(): array
    {
        info('📋 Organization Setup');

        $name = text(
            label: 'Organization Name',
            placeholder: 'Enter organization name...',
            required: true,
            validate: fn (string $value) => match (true) {
                strlen($value) < 2 => 'Organization name must be at least 2 characters.',
                strlen($value) > 100 => 'Organization name must not exceed 100 characters.',
                default => null
            }
        );

        $type = select(
            label: 'Organization Type',
            options: array_map(fn ($case) => $case->value, OrganizationType::cases()),
            default: OrganizationType::Freelancer->value
        );

        $workosChoice = select(
            label: 'WorkOS Integration',
            options: [
                'create' => 'Create new organization in WorkOS',
                'existing' => 'Use existing WorkOS organization',
                'skip' => 'Skip WorkOS integration (manual setup)',
            ],
            default: 'create'
        );

        $workosId = null;
        if ($workosChoice === 'existing') {
            $workosId = text(
                label: 'WorkOS Organization ID',
                placeholder: 'org_...',
                required: true,
                validate: fn (string $value) => match (true) {
                    ! str_starts_with($value, 'org_') => 'WorkOS organization ID must start with "org_"',
                    default => null
                }
            );
        }

        return [
            'name' => $name,
            'type' => OrganizationType::from($type),
            'workos_choice' => $workosChoice,
            'workos_id' => $workosId,
        ];
    }

    /**
     * Prompt for user data
     */
    private function promptForUserData(): array
    {
        info('👤 Admin User Setup');

        $name = text(
            label: 'Full Name',
            placeholder: 'Enter your full name...',
            required: true,
            validate: fn (string $value) => match (true) {
                strlen($value) < 2 => 'Name must be at least 2 characters.',
                default => null
            }
        );

        $email = text(
            label: 'Email Address',
            placeholder: 'Enter your email...',
            required: true,
            validate: fn (string $value) => match (true) {
                ! filter_var($value, FILTER_VALIDATE_EMAIL) => 'Please enter a valid email address.',
                default => null
            }
        );

        $password = password(
            label: 'Password',
            placeholder: 'Enter a secure password...',
            required: true,
            validate: fn (string $value) => match (true) {
                strlen($value) < 8 => 'Password must be at least 8 characters.',
                default => null
            }
        );

        $workosChoice = select(
            label: 'WorkOS User Integration',
            options: [
                'create' => 'Create new user in WorkOS',
                'existing' => 'Use existing WorkOS user',
                'skip' => 'Skip WorkOS integration',
            ],
            default: 'create'
        );

        $workosId = null;
        if ($workosChoice === 'existing') {
            $workosId = text(
                label: 'WorkOS User ID',
                placeholder: 'user_...',
                required: true,
                validate: fn (string $value) => match (true) {
                    ! str_starts_with($value, 'user_') => 'WorkOS user ID must start with "user_"',
                    default => null
                }
            );
        }

        return [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'workos_choice' => $workosChoice,
            'workos_id' => $workosId,
        ];
    }
}
