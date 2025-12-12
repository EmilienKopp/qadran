<?php

namespace App\Http\Middleware;

use App\Models\Landlord\Tenant;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\DB;

class EnsureAvailableTenantsContext
{
    public function handle($request, \Closure $next)
    {
        // Only load tenant data if we have an active tenant context
        // This prevents database errors during OAuth callbacks on the root domain
        if (! Tenant::current()) {
            return $next($request);
        }
        $user = $request->user();
        if (! $user?->id) {
            return $next($request);
        }

        $availableTenants = [];
        if (! Context::has('availableTenants') || empty(Context::get('availableTenants'))) {
            $availableTenants = DB::connection('landlord')->table('tenant_users')
                ->where('user_id', $user->id)
                ->where('email', $user->email)
                ->join('tenants', 'tenants.id', '=', 'tenant_users.tenant_id')
                ->select('tenant_id', 'user_id', 'tenants.name as name', 'tenants.host as host', 'tenants.domain as domain', 'github_user_id', 'google_user_id')
                ->get()
                ->toArray();
        }
        Context::add('availableTenants', $availableTenants);

        return $next($request);
    }
}
