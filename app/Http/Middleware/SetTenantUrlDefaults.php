<?php

namespace App\Http\Middleware;

use App\Support\TenantUrl;
use App\Utils\UrlTools;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
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

        $subdomain = UrlTools::getSubdomain($request->getHost());
        
        Context::add('tenant_subdomain', $subdomain); // for debug/reference only

        return $next($request);
    }
}
