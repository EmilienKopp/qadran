<?php

namespace App\DataAccess\Remote;

use App\DataAccess\{ProjectDataAccess};

class Project extends BaseRemoteAccess implements ProjectDataAccess
{
    protected string $resourceEndpoint = 'api/projects';
}
