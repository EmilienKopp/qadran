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
use WorkOS\Resource\OrganizationMembership;
use WorkOS\UserManagement;

class AddHostToContext
{
    public function __construct(private UserManagement $userApi)
    {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = UrlTools::getSubdomain($request->getHost());
        URL::defaults(['account' => $subdomain ?? Tenant::current()?->host]);

        $account = $request->route('account');
        Context::add('tenant_subdomain', $subdomain); // for debug/reference only

        $tenant = Tenant::firstWhere('host', $account);

        if ($tenant) {
            $tenant->makeCurrent();
            Context::add('domain', $tenant->domain);
            Context::add('host', $tenant->host);
            URL::defaults(['account' => $tenant->host]);
        }

        return $next($request);
    }
}
