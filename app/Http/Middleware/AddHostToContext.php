<?php

namespace App\Http\Middleware;

use App\Support\TenantUrl;
use App\Utils\UrlTools;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

class AddHostToContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = UrlTools::getSubdomain($request->getHost());
        
        Context::add('tenant_subdomain', $subdomain); // for debug/reference only

        return $next($request);
    }
}
