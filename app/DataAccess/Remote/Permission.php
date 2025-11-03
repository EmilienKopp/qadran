<?php

namespace App\DataAccess\Remote;

use App\DataAccess\PermissionDataAccess;
use App\Models\Permission as PermissionModel;

class Permission extends BaseRemoteAccess implements PermissionDataAccess
{
    protected string $model = PermissionModel::class;
}
