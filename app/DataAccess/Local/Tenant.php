<?php

namespace App\DataAccess\Local;

use App\DataAccess\TenantDataAccess;
use App\Models\Landlord\Tenant as TenantModel;

class Tenant extends BaseLocalAccess implements TenantDataAccess
{
  protected string $model = TenantModel::class;

  public function getByDomainOrHost(string $identifier)
  {
    $result = $this->model::where('domain', 'ilike', "%{$identifier}%")
      ->orWhere('host', 'ilike', "%{$identifier}%")
      ->first();
    return $result;
  }
}