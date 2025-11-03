<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\Landlord\Tenant;
use App\Models\Landlord\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

use function Laravel\Prompts\select;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the application';

    private string $TENANT_TEMPLATE_DATABASE = 'tenant_template';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->checkPostgresConnection();
        $this->checkRedisConnection();
        $organization = $this->setupLandlordOrganization();
        $user = $this->setupLandlordUser();
        $this->addUserToLandlordOrganization($organization->id, $user->id);
        $this->createLandlordDatabase($organization->id);
        $this->createTenantTemplateDatabase();
        $this->runLandlordMigrations($organization->id);
        $this->createLandlordUser($user);
        $this->createTenantTemplate($organization->id);
        $this->runTenantMigrations($organization->id);

        $this->info('Adding EUR currency to tenant');

        Tenant::forgetCurrent();
        $tenant = Tenant::where('database', $this->TENANT_TEMPLATE_DATABASE)->first();
        $tenant->makeCurrent();
        if (Currency::where('code', 'EUR')->exists()) {
            $this->info('EUR currency already exists in tenant');

            return;
        }
        Currency::create([
            'code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€',
            'symbol_first' => true,
            'is_default' => true,
            'exchange_rate' => 1,
        ]);
    }

    protected function setEnvValue($key, $value)
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            $this->error('.env file not found.');

            return;
        }

        $envContent = file_get_contents($envPath);

        $pattern = "/^{$key}=.*/m";

        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
        } else {
            $envContent .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $envContent);
    }

    private function checkPostgresConnection()
    {
        $this->info('Checking PostgreSQL connection...');

        try {
            DB::purge('landlord');
            DB::connection()->getPdo();
            $this->info('Database connection successful');
        } catch (\Exception $e) {
            $this->error('Could not connect to the database. Error: '.$e->getMessage());
            $this->error('Please run the following command to start the database:');
            $this->error('docker run --name tired-genius-pg -p 5432:5432 -e POSTGRES_PASSWORD=postgres -d postgres');
            throw $e;
        }
    }

    private function checkRedisConnection()
    {
        $this->info('Checking Redis connection...');

        try {
            Redis::ping();
            $this->info('Redis connection successful');
        } catch (\Exception $e) {
            $this->error('Could not connect to Redis. Error: '.$e->getMessage());
            $this->error('Please run the following command to start Redis:');
            $this->error('docker run --name tired-genius-redis -p 6379:6379 -d redis');
            throw $e;
        }
    }

    private function setupLandlordOrganization()
    {
        $this->info('Setting up Workos landlord organization.');
        \WorkOS\WorkOS::setClientId(config('services.workos.client_id'));
        \WorkOS\WorkOS::setApiKey(config('services.workos.secret'));
        $organizations = new \WorkOS\Organizations;

        $choice = select(
            'Do you want to connect an existing Workos organization or create a new one?',
            [
                'connect' => 'Connect existing organization',
                'create' => 'Create new organization',
                'skip' => 'Skip organization setup (LANDLORD_DOMAIN_ID needs to be set in .env file)',
            ],
            'connect'
        );

        if ($choice === 'skip') {
            $this->info('Skipping organization setup');
            if (! env('LANDLORD_DOMAIN_ID')) {
                $this->error('LANDLORD_DOMAIN_ID is not set in .env file. Please set it to the organization ID to be able to skip this step.');
                throw new \Exception('LANDLORD_DOMAIN_ID is not set in .env file.');
            }
            // Find landlord organization by ID
            $organization = $organizations->getOrganization(env('LANDLORD_DOMAIN_ID'));
            if (! $organization) {
                $this->error('Failed to find Workos organization');
                throw new \Exception('Failed to find Workos organization');
            }
            $this->info('Workos organization found:'.$organization->name);

            return $organization;
        }

        if ($choice === 'connect') {
            $id = $this->ask('Enter the organization ID');
            $organization = $organizations->getOrganization($id);

            if (! $organization) {
                $this->error('Failed to find Workos organization');

                return;
            }

            $this->info('Workos organization found');

            return $organization;
        }

        $name = $this->ask('Enter the name of the new Workos organization');
        $organization = $organizations->createOrganization(name: $name);

        if (! $organization) {
            $this->error('Failed to find Workos organization');

            return;
        }

        $this->info('Workos organization created');

        return $organization;
    }

    private function setupLandlordUser()
    {
        $userManagement = new \WorkOS\UserManagement;

        $choice = select(
            'Do you want to connect an existing Workos user or create a new one?',
            [
                'connect' => 'Connect existing user',
                'create' => 'Create new user',
                'skip' => 'Skip user setup (WORKOS_DEFAULT_USER needs to be set in .env file)',
            ],
            'connect'
        );

        if ($choice === 'skip') {
            $this->info('Skipping user setup');
            if (! env('WORKOS_DEFAULT_USER')) {
                $this->error('WORKOS_DEFAULT_USER is not set in .env file. Please set it to a valid WorkOS user ID to be able to skip this step.');
                throw new \Exception('WORKOS_DEFAULT_USER is not set in .env file.');
            }
            // Find landlord user by ID
            $user = $userManagement->getUser(env('WORKOS_DEFAULT_USER'));
            if (! $user) {
                $this->error('Failed to find Workos user');
                throw new \Exception('Failed to find Workos user');
            }

            return $user;
        }

        if ($this->confirm('Do you want to connect an existing Workos user?')) {
            $id = $this->ask('Enter the user ID');
            $user = $userManagement->getUser($id);

            if (! $user) {
                $this->error('Failed to find Workos user');

                return;
            }

            $this->info('Workos user found');

            return $user;
        }

        $email = $this->ask('Enter the email of the new Workos user');
        $password = $this->ask('Enter the password of the new Workos user');
        $firstName = $this->ask('Enter the first name of the new Workos user');
        $lastName = $this->ask('Enter the last name of the new Workos user');
        $user = $userManagement->createUser(
            email: $email,
            password: $password,
            firstName: $firstName,
            lastName: $lastName
        );

        if (! $user) {
            $this->error('Failed to find Workos user');

            return;
        }

        $this->info('Workos user created');

        return $user;
    }

    private function addUserToLandlordOrganization($organizationId, $userId)
    {
        $userManagement = new \WorkOS\UserManagement;

        $organizationMembership = $userManagement->createOrganizationMembership(
            userId: $userId,
            organizationId: $organizationId,
            roleSlug: 'admin',
        );

        if (! $organizationMembership) {
            $this->error('Failed to add user to organization');
            throw new \Exception('Failed to add user to organization');
        }
    }

    private function createLandlordDatabase($organizationId)
    {
        // Check if the database already exists
        $existingDatabases = DB::connection('landlord')->select('SELECT datname FROM pg_database WHERE datname = ?', [$organizationId]);
        if (! empty($existingDatabases)) {
            $this->info('Database '.$organizationId.' already exists. Skipping creation.');

            return;
        }

        $this->info('Creating landlord database: '.$organizationId);
        $query = 'CREATE DATABASE "'.$organizationId.'"';

        try {
            // Temporarily set the database name
            DB::connection('landlord')->statement($query);
            $this->setEnvValue('DB_DATABASE', $organizationId);

            Config::set('database.connections.landlord.database', $organizationId);

            DB::purge('landlord');

            $this->info('Database '.$organizationId.' created successfully!');
        } catch (\Exception $e) {
            $this->error('Error creating database: '.$e->getMessage());
            // throw $e;
        }
    }

    private function createTenantTemplateDatabase()
    {
        // Check if database exists
        $existingDatabases = DB::connection('landlord')->select('SELECT datname FROM pg_database WHERE datname = ?', [$this->TENANT_TEMPLATE_DATABASE]);
        if (! empty($existingDatabases)) {
            $this->info('Tenant template database '.$this->TENANT_TEMPLATE_DATABASE.' already exists. Skipping creation.');

            return;
        }

        $this->info('Creating tenant template database: '.$this->TENANT_TEMPLATE_DATABASE);
        $query = 'CREATE DATABASE "'.$this->TENANT_TEMPLATE_DATABASE.'"';

        try {
            // Temporarily set the database name
            DB::connection('landlord')->statement($query);

            $this->info('Database '.$this->TENANT_TEMPLATE_DATABASE.' created successfully!');
        } catch (\Exception $e) {
            $this->error('Error creating database: '.$e->getMessage());
            // throw $e;
        }
    }

    private function runLandlordMigrations($organizationId)
    {
        $this->info('Running migrations for landlord organization: '.$organizationId);

        $this->call('migrate', [
            '--database' => 'landlord',
            '--path' => 'database/migrations/landlord',
        ]);
        $this->info('Migrations for landlord organization: '.$organizationId.' ran successfully!');
    }

    private function createLandlordUser($user)
    {
        $this->info('Creating landlord user: '.$user->id);
        if (User::where('workos_id', $user->id)->exists()) {
            $this->info('Landlord user already exists: '.$user->id);

            return;
        }
        User::create([
            'workos_id' => $user->id,
            'email' => $user->email,
            'password' => bcrypt(str()->random(16)),
            'first_name' => $user->firstName,
            'last_name' => $user->lastName,
        ]);
        $this->info('Landlord user created: '.$user->id);
    }

    private function createTenantTemplate($organizationId)
    {
        $this->info('Creating tenant template under org: '.$organizationId);

        try {
            DB::connection('landlord')->table('tenants')->insert([
                'id' => str()->uuid(),
                'name' => 'Tenant Template',
                'database' => $this->TENANT_TEMPLATE_DATABASE,
                'host' => 'tenant_template',
                'domain' => 'tenant_template.localhost',
                'org_id' => 'tenant_template_org',
            ]);

            $this->info('Tenant template created under org: '.$organizationId);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->info('Error creating tenant template. Skipping...');
            // throw $e;
        }
    }

    private function runTenantMigrations($organizationId)
    {
        $this->info('Running migrations for tenant');

        $tenant = Tenant::where('database', $this->TENANT_TEMPLATE_DATABASE)->first();

        if (!$tenant) {
            $this->error('Tenant template not found');
            throw new \Exception('Tenant template not found');
        }

        // Make the tenant current - this sets up the connection
        $tenant->makeCurrent();

        // Configure the tenant connection with the database name
        Config::set('database.connections.tenant.database', $tenant->database);
        DB::purge('tenant');

        $this->info('Running migrations on tenant database: ' . $tenant->database);

        $this->call('migrate', [
            '--database' => 'tenant',
            '--force' => true,
        ]);

        Tenant::forgetCurrent();

        $this->info('Migrations for tenant: '.$tenant->id.' ran successfully!');
    }
}
