<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\Landlord\Tenant;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use WorkOS\Organizations;
use WorkOS\UserManagement;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

class OrganizationSetupService
{
    /**
     * Create organization and user with tenant
     */
    public function createOrganizationAndUser(array $orgData, array $userData): array
    {
        // Create organization in landlord database
        $organization = $this->createOrganization($orgData);

        // Create tenant for organization
        $tenant = $this->createTenant($organization, $orgData);

        // Switch to tenant context and create user with roles
        $user = $this->createUserInTenant($tenant, $userData, $organization);

        return [
            'organization' => $organization,
            'tenant' => $tenant,
            'user' => $user,
        ];
    }

    /**
     * Create organization in landlord database
     */
    public function createOrganization(array $orgData): Organization
    {
        // Switch to landlord connection
        DB::setDefaultConnection('landlord');

        // Check if organization already exists
        $existing = Organization::where('name', $orgData['name'])->first();

        if ($existing) {
            warning("  ⚠️  Organization '{$orgData['name']}' already exists - using existing");

            return $existing;
        }

        // Prepare organization data for creation
        $createData = [
            'name' => $orgData['name'],
            'type' => $orgData['type']->value,
            'description' => $orgData['name'].' organization',
        ];

        // Handle WorkOS integration
        if (isset($orgData['workos_choice']) && $orgData['workos_choice'] === 'existing' && $orgData['workos_id']) {
            $createData['workos_id'] = $orgData['workos_id'];
        } elseif (isset($orgData['workos_choice']) && $orgData['workos_choice'] === 'create') {
            $createData['workos_id'] = $this->createWorkOSOrganization($orgData);
        }

        $organization = Organization::create($createData);
        info("  ✅ Created organization: {$organization->name}");

        return $organization;
    }

    /**
     * Create organization in WorkOS
     */
    public function createWorkOSOrganization(array $orgData): string
    {
        try {
            $organizations = new Organizations;

            $workosOrg = $organizations->createOrganization($orgData['name']);

            info("  ✅ Created WorkOS organization: {$workosOrg->id}");

            return $workosOrg->id;

        } catch (Exception $e) {
            error('  ❌ Failed to create WorkOS organization: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Create tenant for organization
     */
    public function createTenant(Organization $organization, array $orgData): Tenant
    {
        $database = 'qadran_'.Str::slug($orgData['name'], '_');
        $domain = Str::slug($orgData['name']).'.local';

        // Check if tenant already exists
        $existing = Tenant::where('org_id', $organization->id)->first();

        if ($existing) {
            warning('  ⚠️  Tenant for organization already exists - using existing');

            return $existing;
        }

        // Create tenant database by copying from template
        $this->createTenantDatabase($database);

        $tenant = Tenant::create([
            'name' => $organization->name,
            'database' => $database,
            'domain' => $domain,
            'host' => Str::slug($orgData['name']),
            'org_id' => $organization->id,
        ]);

        info("  ✅ Created tenant: {$tenant->name} (Database: {$database})");

        return $tenant;
    }

    /**
     * Create tenant database from template
     */
    public function createTenantDatabase(string $database): void
    {
        $templateDb = env('DB_DATABASE', 'tenant').'_template';

        try {
            $pdo = new \PDO(
                'pgsql:host='.env('DB_HOST').';port='.env('DB_PORT', 5432),
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            );

            // Check if database exists
            $stmt = $pdo->prepare('SELECT 1 FROM pg_database WHERE datname = ?');
            $stmt->execute([$database]);

            if ($stmt->fetch()) {
                warning("    ⚠️  Tenant database '{$database}' already exists - skipping creation");

                return;
            }

            // Create database from template
            $pdo->exec("CREATE DATABASE \"{$database}\" WITH TEMPLATE \"{$templateDb}\"");
            info("    ✅ Created tenant database: {$database}");

        } catch (Exception $e) {
            error('    ❌ Failed to create tenant database: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Create user in tenant context with roles
     */
    public function createUserInTenant(Tenant $tenant, array $userData, Organization $organization): User
    {
        // Make tenant current
        $tenant->makeCurrent();

        try {
            // Verify we're in the correct tenant context
            $currentDb = DB::connection()->getDatabaseName();
            info("    Working in tenant database: {$currentDb}");

            // Check if user already exists
            $existing = User::where('email', $userData['email'])->first();

            if ($existing) {
                warning("  ⚠️  User '{$userData['email']}' already exists in tenant - using existing");

                return $existing;
            }

            // Handle WorkOS user integration
            $workosId = null;
            if (isset($userData['workos_choice']) && $userData['workos_choice'] === 'existing' && $userData['workos_id']) {
                $workosId = $userData['workos_id'];
            } elseif (isset($userData['workos_choice']) && $userData['workos_choice'] === 'create') {
                $workosId = $this->createWorkOSUser($userData);
            }

            // Create user
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'workos_id' => $workosId,
                'email_verified_at' => now(),
            ]);

            // Create roles if they don't exist
            $this->createRolesInTenant();

            // Assign roles to user
            $user->assignRole([RoleEnum::Admin, RoleEnum::BusinessOwner]);

            // Link user to organization (using the organization ID from landlord)
            OrganizationUser::create([
                'user_id' => $user->id,
                'organization_id' => $organization->id,
                'elevated' => true,
            ]);

            info("  ✅ Created admin user: {$user->email}");
            info('  ✅ Assigned roles: Admin, Business Owner');

            return $user;

        } catch (Exception $e) {
            error('  ❌ Failed to create user in tenant: '.$e->getMessage());
            throw $e;
        } finally {
            // Reset database connection to default
            DB::setDefaultConnection(config('database.default'));
        }
    }

    /**
     * Create user in WorkOS
     */
    public function createWorkOSUser(array $userData): string
    {
        try {
            $userManagement = new UserManagement;

            $nameParts = explode(' ', $userData['name'], 2);
            $firstName = $nameParts[0] ?? $userData['name'];
            $lastName = $nameParts[1] ?? '';

            $workosUser = $userManagement->createUser(
                $userData['email'],
                $userData['password'],
                $firstName,
                $lastName,
                true // emailVerified
            );

            info("  ✅ Created WorkOS user: {$workosUser->id}");

            return $workosUser->id;

        } catch (Exception $e) {
            error('  ❌ Failed to create WorkOS user: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Create roles in tenant database
     */
    public function createRolesInTenant(): void
    {
        try {
            foreach (RoleEnum::values() as $roleName) {
                if (! Role::where('name', $roleName)->exists()) {
                    Role::create(['name' => $roleName]);
                }
            }

            info('    ✅ Roles created/verified in tenant');
        } catch (Exception $e) {
            error('    ❌ Failed to create roles: '.$e->getMessage());
            throw $e;
        }
    }
}
