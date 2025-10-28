<?php

namespace App\Services;

use App\Enums\ExecutionContext;
use App\Support\RequestContextResolver;
use Illuminate\Http\Request;
use App\Models\Landlord\Tenant;
use App\DataAccess\Facades\Tenant as TenantFacade;
use Illuminate\Support\Facades\Context;
use Spatie\Multitenancy\TenantFinder\TenantFinder as BaseTenantFinder;

class TenantFinder extends BaseTenantFinder
{
  public function findForRequest(Request $request): ?Tenant
  {
    $host = RequestContextResolver::getHost();
    $isLocal = ! app()->isProduction() && ($host === 'localhost' || str($host)->contains('127.0.0.1'));
    // if (! app()->isProduction() && ($host === 'localhost' || str($host)->contains('127.0.0.1'))) {
    //   $tenant = TenantFacade::firstWhere('domain', 'qadranio.com');
    // } else {
    //   $subdomain = explode('.', $host)[0];
    //   $tenant = TenantFacade::firstWhere('host', $subdomain);
    // }
    return match(RequestContextResolver::getExecutionContext()) {
      ExecutionContext::DESKTOP => TenantFacade::firstWhere('domain', 'qadranio.com'),
      ExecutionContext::WEB, => $isLocal 
        ? TenantFacade::firstWhere('domain', 'qadranio.com')
        : TenantFacade::firstWhere('host', explode('.', $host)[0]),
      default => TenantFacade::firstWhere('host', explode('.', $host)[0]),
    };
  }
}