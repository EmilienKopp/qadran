<?php

namespace App\DataAccess\Remote;

use App\DataAccess\RoleDataAccess;
use App\Models\Role as RoleModel;

class Role extends BaseRemoteAccess implements RoleDataAccess
{
    protected string $model = RoleModel::class;
}
