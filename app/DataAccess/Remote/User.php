<?php

namespace App\DataAccess\Remote;

use App\DataAccess\{UserDataAccess};
use App\Models\User as UserModel;

class User extends BaseRemoteAccess implements UserDataAccess
{
    protected string $model = UserModel::class;

    // Override getEndpoint to use singular 'user' to match API route pattern
    protected function getEndpoint()
    {
        return "{$this->baseUrl}/api/user";
    }
}
