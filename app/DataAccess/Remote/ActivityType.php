<?php

namespace App\DataAccess\Remote;

use App\DataAccess\ActivityTypeDataAccess;
use App\Models\ActivityType as ActivityTypeModel;

class ActivityType extends BaseRemoteAccess implements ActivityTypeDataAccess
{
    protected string $model = ActivityTypeModel::class;
}
