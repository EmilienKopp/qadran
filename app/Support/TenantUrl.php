<?php

namespace App\Support;

use App\Models\Landlord\Tenant;
use Illuminate\Support\Facades\URL;

class TenantUrl
{
    /**
     * Generate a route URL with proper tenant context handling.
     */
    public static function route(string $name, array $parameters = [], bool $absolute = true): string
    {
        $tenant = Tenant::current();

        if (! $tenant) {
            return route($name, $parameters, $absolute);
        }

        // In production (non-staging), subdomain routing is used
        if (app()->isProduction() && ! app()->environment('staging')) {
            // Don't pass account parameter - it's in the subdomain
            return route($name, $parameters, $absolute);
        }

        // In staging, use path prefix
        if (app()->environment('staging')) {
            // Add account as a route parameter
            return route($name, array_merge(['account' => $tenant->host], $parameters), $absolute);
        }

        // Local development - no account parameter needed
        return route($name, $parameters, $absolute);
    }

    /**
     * Set default route parameters based on current tenant and environment.
     */
    public static function setDefaultParameters(): void
    {
        $tenant = Tenant::current();

        if (! $tenant) {
            return;
        }

        // Set defaults for both staging (prefix) and production (domain) routing
        if (app()->environment('staging')) {
            // Staging uses path prefix
            URL::defaults(['account' => $tenant->host]);
        } elseif (app()->isProduction()) {
            // Production uses subdomain - set domain defaults
            URL::defaults(['account' => $tenant->host]);
        }
    }
}
