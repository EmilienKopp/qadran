<?php

namespace App\DataAccess\Remote;

use App\DataAccess\ActivityLogDataAccess;
use App\Models\ActivityLog as ActivityLogModel;

class ActivityLog extends BaseRemoteAccess implements ActivityLogDataAccess
{
    protected string $model = ActivityLogModel::class;
}
