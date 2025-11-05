<?php

namespace App\Services;

use App\DataAccess\Facades\Tenant as TenantFacade;
use App\Enums\ExecutionContext;
use App\Models\Landlord\Tenant;
use App\Support\RequestContextResolver;
use Illuminate\Http\Request;
use Native\Desktop\Facades\Settings;
use Spatie\Multitenancy\TenantFinder\TenantFinder as BaseTenantFinder;

class TenantFinder extends BaseTenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        \Log::debug('🔍 TenantFinder::findForRequest called', [
            'has_route' => $request->route() !== null,
            'route_params' => $request->route()?->parameters() ?? 'no route yet',
            'host' => $request->getHost(),
        ]);

        $host = RequestContextResolver::getHost();
        $context = RequestContextResolver::getExecutionContext();

        $isLocal = ! app()->isProduction() && ($host === 'localhost' || str($host)->contains('127.0.0.1'));

        return match ($context) {
            ExecutionContext::DESKTOP => $this->findDesktopTenant(),
            ExecutionContext::WEB, => $this->findWebTenant($host, $isLocal),
            default => TenantFacade::firstWhere('host', explode('.', $host)[0]),
        };
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
            \Log::debug('Finding local web tenant', ['host' => $host]);

            return TenantFacade::firstWhere('domain', 'qadranio.com');
        }

        // In production, extract account from subdomain or route
        // Route parameter might not be available yet, so parse from host
        $account = $this->extractAccountFromHost($host);

        \Log::debug('Finding production web tenant', [
            'host' => $host,
            'extracted_account' => $account,
            'route_param' => RequestContextResolver::getAccountParameter(),
        ]);

        return TenantFacade::firstWhere('host', $account);
    }

    /**
     * Extract account identifier from host.
     * Works for both subdomain (account.qadran.io) and path-based routing.
     */
    private function extractAccountFromHost(string $host): ?string
    {
        // First try route parameter (if route matching already happened)
        $routeAccount = RequestContextResolver::getAccountParameter();
        if ($routeAccount) {
            return $routeAccount;
        }

        // Fallback: Extract from subdomain
        // For "tenant.qadran.io", extract "tenant"
        $parts = explode('.', $host);

        // If we have subdomain (e.g., tenant.qadran.io has 3 parts)
        if (count($parts) >= 3) {
            return $parts[0]; // Return the subdomain
        }

        return null;
    }
}
