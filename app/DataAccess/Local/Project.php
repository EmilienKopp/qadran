<?php

namespace App\DataAccess\Local;

use App\DataAccess\{ProjectDataAccess};
use App\Models\Project as ProjectModel;

class Project extends BaseLocalAccess implements ProjectDataAccess
{
    protected string $model = ProjectModel::class;
}
