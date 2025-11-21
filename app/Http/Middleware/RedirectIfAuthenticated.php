<?php

namespace App\Http\Middleware;

use App\Models\Landlord\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $tenant = Tenant::current();

                // If on a tenant subdomain, redirect to dashboard
                if ($tenant) {
                    // Build parameters based on environment and tenant
                    $params = [];
                    if (app()->environment('staging') || app()->isProduction()) {
                        $params['account'] = $tenant->host;
                    }

                    return redirect()->route('dashboard', $params);
                }

                // If on root domain (no tenant), redirect to tenant root (which shows landing)
                return redirect()->route('tenant.root');
            }
        }

        return $next($request);
    }
}
