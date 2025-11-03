<?php

namespace App\DataAccess\Local;

use App\DataAccess\ActivityTypeDataAccess;
use App\Models\ActivityType as ActivityTypeModel;

class ActivityType extends BaseLocalAccess implements ActivityTypeDataAccess
{
    protected string $model = ActivityTypeModel::class;
}
