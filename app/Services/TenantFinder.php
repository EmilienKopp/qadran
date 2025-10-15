<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Landlord\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder as BaseTenantFinder;

class TenantFinder extends BaseTenantFinder
{
  public function findForRequest(Request $request): ?Tenant
  {
    $host = $request->getHost();
    if ($host === 'localhost') {
      return Tenant::firstWhere('domain', 'qadranio.com');
    }
    $subdomain = explode('.', $host)[0];
    return Tenant::firstWhere('host', $subdomain)->first();
  }
}