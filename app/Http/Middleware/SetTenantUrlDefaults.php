<?php

namespace App\Http\Middleware;

use App\Models\Landlord\Tenant;
use App\Support\TenantUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantUrlDefaults
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set default URL parameters based on tenant and environment
        TenantUrl::setDefaultParameters();

        return $next($request);
    }
}
