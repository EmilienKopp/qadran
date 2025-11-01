<?php

namespace App\DataAccess\Remote;

use App\DataAccess\{UserDataAccess};

class User extends BaseRemoteAccess implements UserDataAccess
{
    // Override getEndpoint to use singular 'user' to match API route pattern
    protected function getEndpoint()
    {
        return "{$this->baseUrl}/api/user";
    }
}
