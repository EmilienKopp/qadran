<?php

namespace App\Repositories\Landlord\DB;


use App\Models\Landlord\Tenant;
use App\Repositories\Interfaces\TenantRepository;
use App\Repositories\Repository;

class DBTenant implements TenantRepository 
{
  protected static $model = Tenant::class;

  public static function find($id) {
    return static::$model::find($id);
  }

  public static function firstWhere($column, $operator, $value = null) {
    return static::$model::firstWhere($column, $operator, $value);
  }


}