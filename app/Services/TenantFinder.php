<?php

namespace App\Services;

use App\DataAccess\Facades\Tenant as TenantFacade;
use App\Enums\ExecutionContext;
use App\Models\Landlord\Tenant;
use App\Support\RequestContextResolver;
use App\Utils\UrlTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\DB;
use Native\Desktop\Facades\Settings;
use Spatie\Multitenancy\TenantFinder\TenantFinder as BaseTenantFinder;

class TenantFinder extends BaseTenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        $host = RequestContextResolver::getHost();
        $context = RequestContextResolver::getExecutionContext();

        $isLocal = ! app()->isProduction() && ($host === 'localhost' || str($host)->contains('127.0.0.1'));

        $tenant = match ($context) {
            ExecutionContext::DESKTOP => $this->findDesktopTenant(),
            ExecutionContext::WEB, => $this->findWebTenant($host, $isLocal),
            default => TenantFacade::firstWhere('host', explode('.', $host)[0]),
        };
        Context::add('domain', $tenant?->domain);
        Context::add('host', $tenant?->host);
        Context::add('tenantId', $tenant?->id);
        Context::add('executionContext', $context);
        return $tenant;
    }

    private function findDesktopTenant(): ?Tenant
    {
        $settingsTenant = Settings::get('tenant');
        if (! $settingsTenant) {
            return null;
        }
        $tenant = new Tenant($settingsTenant);

        return $tenant;
    }

    private function findWebTenant(string $host, bool $isLocal): ?Tenant
    {
        if ($isLocal) {
            $tenant = TenantFacade::firstWhere('domain', 'qadranio.com');

            return $tenant;
        }

        // Route parameter might not be available yet, so parse from host
        $account = $this->extractAccountFromHost($host);

        return TenantFacade::firstWhere('host', $account);
    }

    /**
     * Extract account identifier from host.
     * Works for both subdomain (account.qadran.io) and path-based routing.
     */
    private function extractAccountFromHost(string $host): ?string
    {
        $routeAccount = RequestContextResolver::getAccountParameter();
        if ($routeAccount) {
            return $routeAccount;
        }

        return UrlTools::getSubdomain($host);
    }
}
