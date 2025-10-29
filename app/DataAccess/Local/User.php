<?php

namespace App\DataAccess\Local;

use App\DataAccess\{UserDataAccess};
use App\Models\User as UserModel;

class User extends BaseLocalAccess implements UserDataAccess
{
    protected string $model = User::class;
}
