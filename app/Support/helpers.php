<?php

use Spatie\Multitenancy\Models\Tenant;


if(! function_exists('tenant')) {
    /**
     * Get the current tenant.
     */
    function tenant()
    {
        return Tenant::current();
    }
}

if(! function_exists('account')) {
    /**
     * Get the current tenant's ID.
     */
    function account()
    {
        return request()->route('account');
    }
}

if(! function_exists('timezone')) {
    /**
     * Get the current user OR tenant timezone.
     */
    function timezone()
    {
        return auth('tenant')->user()?->timezone
            ?? Tenant::current()?->timezone
            ?? config('app.timezone', 'UTC');
    }
}