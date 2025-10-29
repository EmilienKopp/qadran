<?php

namespace App\DataAccess\Remote;

use App\DataAccess\{UserDataAccess};

class User extends BaseRemoteAccess implements UserDataAccess
{
    protected string $resourceEndpoint = 'api/users';
}
