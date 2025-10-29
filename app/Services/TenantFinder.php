<?php

namespace App\Services;

use App\Enums\ExecutionContext;
use App\Support\RequestContextResolver;
use Illuminate\Http\Request;
use App\Models\Landlord\Tenant;
use App\DataAccess\Facades\Tenant as TenantFacade;
use Illuminate\Support\Facades\Context;
use Native\Desktop\Alert;
use Native\Desktop\Dialog;
use Native\Desktop\Facades\Settings;
use Spatie\Multitenancy\TenantFinder\TenantFinder as BaseTenantFinder;

class TenantFinder extends BaseTenantFinder
{
  public function findForRequest(Request $request): ?Tenant
  {
    $host = RequestContextResolver::getHost();
    $context = RequestContextResolver::getExecutionContext();

    $isLocal = ! app()->isProduction() && ($host === 'localhost' || str($host)->contains('127.0.0.1'));

    return match($context) {
      ExecutionContext::DESKTOP => $this->findDesktopTenant(),
      ExecutionContext::WEB, => $this->findWebTenant($host, $isLocal),
      default => TenantFacade::firstWhere('host', explode('.', $host)[0]),
    };
  }

  private function findDesktopTenant(): ?Tenant
  {
    $tenant = new Tenant(Settings::get('tenant'));
    return $tenant;
  }

  private function findWebTenant(string $host, bool $isLocal): ?Tenant
  {
    return $isLocal
      ? TenantFacade::firstWhere('domain', 'qadranio.com')
      : TenantFacade::firstWhere('host', explode('.', $host)[0]);
  }
}