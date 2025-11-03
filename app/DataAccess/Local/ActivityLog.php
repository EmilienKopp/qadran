<?php

namespace App\DataAccess\Local;

use App\DataAccess\ActivityLogDataAccess;
use App\Models\ActivityLog as ActivityLogModel;

class ActivityLog extends BaseLocalAccess implements ActivityLogDataAccess
{
    protected string $model = ActivityLogModel::class;
}
