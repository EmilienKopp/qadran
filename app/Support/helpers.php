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