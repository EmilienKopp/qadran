<?php

namespace App\DataAccess\Local;

use App\DataAccess\RoleDataAccess;
use App\Models\Role as RoleModel;

class Role extends BaseLocalAccess implements RoleDataAccess
{
    protected string $model = RoleModel::class;
}
