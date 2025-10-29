<?php

namespace App\DataAccess\Remote;

use App\DataAccess\{OrganizationDataAccess};

class Organization extends BaseRemoteAccess implements OrganizationDataAccess
{
    protected string $resourceEndpoint = 'api/organizations';
}
