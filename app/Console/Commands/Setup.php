<?php

namespace App\Console\Commands;

use App\Enums\RoleEnum;
use App\Enums\OrganizationType;
use App\Models\Landlord\Tenant;
use App\Models\Organization;
use App\Models\User;
use App\Models\OrganizationUser;
use App\Services\OrganizationSetupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Permission\Models\Role;
use WorkOS\WorkOS;
use WorkOS\Organizations;
use WorkOS\UserManagement;
use Exception;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\error;
use function Laravel\Prompts\spin;

/**
 * Setup Command for Qadran Application
 * 
 * This command sets up the complete Qadran multi-tenant application from scratch.
 * It's designed to be safe for both development and production environments.
 * 
 * Features:
 * - Creates landlord and tenant template databases
 * - Runs all necessary migrations
 * - Creates the first organization and admin user
 * - Sets up roles and permissions
 * - Gracefully handles existing data
 * - Includes safety checks for production use
 * 
 * Usage Examples:
 * - Development: php artisan app:setup
 * - Production: php artisan app:setup --force
 * - Automated: php artisan app:setup --skip-prompts --org-name="My Org" --admin-email="admin@example.com" --admin-password="secure123"
 * - Preview: php artisan app:setup --dry-run
 */

class Setup extends Command
{
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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup 
                            {--force : Force setup even in production}
                            {--skip-prompts : Skip interactive prompts and use defaults}
                            {--skip-org : Skip organization and user creation}
                            {--dry-run : Show what would be done without executing}
                            {--org-name= : Organization name (skips prompt)}
                            {--org-type= : Organization type (skips prompt)}
                            {--workos-org-id= : Existing WorkOS organization ID}
                            {--admin-email= : Admin email (skips prompt)}
                            {--admin-name= : Admin name (skips prompt)}
                            {--admin-password= : Admin password (skips prompt)}
                            {--workos-user-id= : Existing WorkOS user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the application for development or production environments with WorkOS integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('🚀 Starting Qadran Application Setup...');

        // Environment safety check
        if (!$this->environmentCheck()) {
            return Command::FAILURE;
        }

        // Check WorkOS configuration
        if (!$this->checkWorkOSConfiguration()) {
            return Command::FAILURE;
        }

        // Dry run mode
        if ($this->option('dry-run')) {
            return $this->dryRun();
        }

        try {
            // Step 1: Setup databases
            $this->setupDatabases();

            // Step 2: Run migrations
            $this->runMigrations();

            // Step 3: Create organization and user (optional)
            if (!$this->option('skip-org') && confirm('Do you want to create the first organization and admin user now?', true)) {
                $this->createOrganizationAndUser();
            } else {
                info('⏭️  Skipping organization and user creation');
            }

            info('');
            info('✅ Setup completed successfully!');
            info('🎉 Your Qadran application is ready to use.');
            
            return Command::SUCCESS;

        } catch (Exception $e) {
            error('❌ Setup failed: ' . $e->getMessage());
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

        if (!$apiKey || !$clientId) {
            error('❌ WorkOS is not properly configured');
            info('Please set WORKOS_API_KEY and WORKOS_CLIENT_ID in your .env file');
            return false;
        }

        try {
            // Test WorkOS connection
            WorkOS::setApiKey($apiKey);
            $organizations = new Organizations();
            $organizations->listOrganizations(limit:1);
            info('✅ WorkOS connection successful');
            return true;
        } catch (Exception $e) {
            error('❌ WorkOS connection failed: ' . $e->getMessage());
            return false;
        }
    }
    private function dryRun(): int
    {
        info('🔍 DRY RUN MODE - Showing what would be done:');
        info('');

        $landlordDb = env('DB_DATABASE', 'qadran_landlord');
        $templateDb = 'tenant_template';

        info("📊 Database Setup:");
        info("  - Create landlord database: {$landlordDb}");
        info("  - Create tenant template database: {$templateDb}");
        info("  - Run landlord migrations");
        info("  - Run tenant template migrations");
        info('');

        if (!$this->option('skip-org')) {
            $orgData = $this->getOrganizationDetails();
            $userData = $this->getUserDetails();

            info("👥 Organization & User Setup:");
            info("  - Create organization: {$orgData['name']} ({$orgData['type']->value})");
            if (isset($orgData['workos_id'])) {
                info("  - Use existing WorkOS org: {$orgData['workos_id']}");
            } else {
                info("  - Create new WorkOS organization");
            }
            info("  - Create tenant database: qadran_" . Str::slug($orgData['name'], '_'));
            info("  - Create admin user: {$userData['email']}");
            if (isset($userData['workos_id'])) {
                info("  - Use existing WorkOS user: {$userData['workos_id']}");
            } else {
                info("  - Create new WorkOS user");
            }
            info("  - Assign roles: Admin, Business Owner");
        } else {
            info("👥 Organization & User Setup:");
            info("  - ⏭️  Skipping organization and user creation");
        }
        info('');

        warning('⚠️  This was a dry run. Remove --dry-run to execute the setup.');
        
        return Command::SUCCESS;
    }

    /**
     * Check environment and confirm setup
     */
    private function environmentCheck(): bool
    {
        $env = app()->environment();
        info("Environment: {$env}");

        if ($env === 'production' && !$this->option('force')) {
            warning('⚠️  Running setup in PRODUCTION environment!');
            if (!confirm('Are you sure you want to continue?')) {
                info('Setup cancelled for safety.');
                return false;
            }
        }

        // Check required environment variables
        $required = ['DB_HOST', 'DB_USERNAME', 'DB_PASSWORD'];
        $missing = [];

        foreach ($required as $var) {
            if (!env($var)) {
                $missing[] = $var;
            }
        }

        if (!empty($missing)) {
            error('Missing required environment variables: ' . implode(', ', $missing));
            return false;
        }

        // Test database connection
        if (!$this->testDatabaseConnection()) {
            return false;
        }

        return true;
    }

    /**
     * Test database connection
     */
    private function testDatabaseConnection(): bool
    {
        try {
            $pdo = new \PDO(
                "pgsql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT', 5432),
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            );
            info('✅ Database connection successful');
            return true;
        } catch (Exception $e) {
            error('❌ Database connection failed: ' . $e->getMessage());
            info('Please check your database configuration in .env file');
            return false;
        }
    }

    /**
     * Setup landlord and tenant template databases
     */
    private function setupDatabases(): void
    {
        info('📊 Setting up databases...');

        // Setup landlord database
        $this->setupLandlordDatabase();

        // Setup tenant template database
        $this->setupTenantTemplateDatabase();
    }

    /**
     * Setup landlord database
     */
    private function setupLandlordDatabase(): void
    {
        $landlordDb = env('DB_DATABASE', 'qadran_landlord');
        
        info("Creating landlord database: {$landlordDb}");

        try {
            // Connect to postgres without database to create it
            $pdo = new \PDO(
                "pgsql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT', 5432),
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            );

            // Check if database exists
            $stmt = $pdo->prepare("SELECT 1 FROM pg_database WHERE datname = ?");
            $stmt->execute([$landlordDb]);

            if ($stmt->fetch()) {
                $this->warn("  ⚠️  Landlord database '{$landlordDb}' already exists - skipping creation");
            } else {
                $pdo->exec("CREATE DATABASE \"{$landlordDb}\"");
                info("  ✅ Created landlord database: {$landlordDb}");
            }

        } catch (Exception $e) {
            $this->error("  ❌ Failed to create landlord database: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Setup tenant template database
     */
    private function setupTenantTemplateDatabase(): void
    {
        $templateDb = 'tenant_template';
        
        info("Creating tenant template database: {$templateDb}");

        try {
            $pdo = new \PDO(
                "pgsql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT', 5432),
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            );

            // Check if database exists
            $stmt = $pdo->prepare("SELECT 1 FROM pg_database WHERE datname = ?");
            $stmt->execute([$templateDb]);

            if ($stmt->fetch()) {
                $this->warn("  ⚠️  Template database '{$templateDb}' already exists - skipping creation");
            } else {
                $pdo->exec("CREATE DATABASE \"{$templateDb}\"");
                info("  ✅ Created template database: {$templateDb}");
            }

        } catch (Exception $e) {
            $this->error("  ❌ Failed to create template database: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Run migrations for landlord and tenant template
     */
    private function runMigrations(): void
    {
        info('🔄 Running migrations...');

        // Run landlord migrations
        info('  Running landlord migrations...');
        try {
            Artisan::call('migrate', [
                '--path' => 'database/migrations/landlord',
                '--database' => 'landlord',
                '--force' => true
            ], $this->getOutput());
            
            info('  ✅ Landlord migrations completed');
        } catch (Exception $e) {
            error('  ❌ Landlord migrations failed: ' . $e->getMessage());
            throw $e;
        }


        // Run tenant template migrations
        info('  Running tenant template migrations...');
        try {
            $templateDb = env('DB_DATABASE', 'qadran_landlord') . '_template';
            
            // Temporarily set tenant connection to template database
            config(['database.connections.tenant.database' => $templateDb]);
            DB::purge('tenant');

            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--force' => true
            ], $this->getOutput());
            info('  ✅ Tenant template migrations completed');
        } catch (Exception $e) {
            error('  ❌ Tenant template migrations failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create organization and user
     */
    private function createOrganizationAndUser(): void
    {
        info('👥 Creating organization and user...');

        // Get organization details
        $orgData = $this->getOrganizationDetails();
        
        // Get user details
        $userData = $this->getUserDetails();

        // Use the service to create organization and user
        $result = $this->setupService->createOrganizationAndUser($orgData, $userData);

        info("  ✅ Organization setup complete!");
        info("  🏢 Organization: {$result['organization']->name}");
        info("  👤 Admin User: {$result['user']->email}");
        info("  🏠 Tenant Database: {$result['tenant']->database}");
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
                'workos_id' => $this->option('workos-org-id')
            ];

            return $orgData;
        }

        // Check if skipping prompts
        if ($this->option('skip-prompts')) {
            return [
                'name' => 'Default Organization',
                'type' => OrganizationType::Corporation,
                'workos_choice' => 'skip',
                'workos_id' => null
            ];
        }

        // Interactive mode - use the new Laravel Prompts
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
                'workos_id' => $this->option('workos-user-id')
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
                'workos_id' => null
            ];
        }

        // Interactive mode - use the new Laravel Prompts
        return $this->promptForUserData();
    }

    /**
     * Prompt for organization data
     */
    private function promptForOrganizationData(): array
    {
        info("📋 Organization Setup");
        
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
                'skip' => 'Skip WorkOS integration (manual setup)'
            ],
            default: 'create'
        );

        $workosId = null;
        if ($workosChoice === 'existing') {
            $workosId = $this->text(
                label: 'WorkOS Organization ID',
                placeholder: 'org_...',
                required: true,
                validate: fn (string $value) => match (true) {
                    !str_starts_with($value, 'org_') => 'WorkOS organization ID must start with "org_"',
                    default => null
                }
            );
        }

        return [
            'name' => $name,
            'type' => OrganizationType::from($type),
            'workos_choice' => $workosChoice,
            'workos_id' => $workosId
        ];
    }

    /**
     * Prompt for user data
     */
    private function promptForUserData(): array
    {
        info("👤 Admin User Setup");
        
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
                !filter_var($value, FILTER_VALIDATE_EMAIL) => 'Please enter a valid email address.',
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
                'skip' => 'Skip WorkOS integration'
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
                    !str_starts_with($value, 'user_') => 'WorkOS user ID must start with "user_"',
                    default => null
                }
            );
        }

        return [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'workos_choice' => $workosChoice,
            'workos_id' => $workosId
        ];
    }

    /**
     * Cleanup partial setup on failure
     */
    private function cleanup(string $step, Exception $error): void
    {
        error("Setup failed at step: {$step}");
        error("Error: " . $error->getMessage());
        
        if (confirm('Would you like to attempt cleanup of partial setup?')) {
            info('Performing cleanup...');
            // Add cleanup logic here if needed
            warning('Manual cleanup may be required. Check your databases.');
        }
    }
}
