<?php

namespace App\DataAccess\Local;

use App\DataAccess\RepositorySettingsDataAccess;
use App\Models\RepositorySettings as RepositorySettingsModel;

class RepositorySettings extends BaseLocalAccess implements RepositorySettingsDataAccess
{
    protected string $model = RepositorySettingsModel::class;
}
