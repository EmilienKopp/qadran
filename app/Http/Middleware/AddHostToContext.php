<?php

namespace App\Http\Middleware;

use App\Models\Landlord\Tenant;
use App\Support\TenantUrl;
use App\Utils\UrlTools;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class AddHostToContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = UrlTools::getSubdomain($request->getHost());

        URL::defaults(['account' => $subdomain ?? Tenant::current()?->host]);
        
        Context::add('tenant_subdomain', $subdomain); // for debug/reference only

        return $next($request);
    }
}
