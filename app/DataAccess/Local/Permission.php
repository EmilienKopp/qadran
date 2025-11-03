<?php

namespace App\DataAccess\Local;

use App\DataAccess\PermissionDataAccess;
use App\Models\Permission as PermissionModel;

class Permission extends BaseLocalAccess implements PermissionDataAccess
{
    protected string $model = PermissionModel::class;
}
