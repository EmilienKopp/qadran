<?php

namespace App\DataAccess\Remote;

use App\DataAccess\RepositorySettingsDataAccess;
use App\Models\RepositorySettings as RepositorySettingsModel;

class RepositorySettings extends BaseRemoteAccess implements RepositorySettingsDataAccess
{
    protected string $model = RepositorySettingsModel::class;
}
