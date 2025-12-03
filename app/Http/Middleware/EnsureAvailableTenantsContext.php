<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\{Context, DB};

class EnsureAvailableTenantsContext
{
  public function handle($request, \Closure $next)
  {
    $user = $request->user();
    if (!$user?->id) {
      return $next($request);
    }

    $availableTenants = [];
    if (!Context::has('availableTenants') || empty(Context::get('availableTenants'))) {
      $availableTenants = DB::table('tenant_users')
        ->where('user_id', $user->id)
        ->join('tenants', 'tenants.id', '=', 'tenant_users.tenant_id')
        ->select('tenant_id', 'user_id', 'tenants.name as name', 'tenants.host as host', 'tenants.domain as domain', 'github_user_id', 'google_user_id')
        ->get()
        ->toArray();
    }
    Context::add('availableTenants', $availableTenants);

    return $next($request);
  }
}