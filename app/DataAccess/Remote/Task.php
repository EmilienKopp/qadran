<?php

namespace App\DataAccess\Remote;

use App\DataAccess\TaskDataAccess;
use App\Models\Task as TaskModel;

class Task extends BaseRemoteAccess implements TaskDataAccess
{
    protected string $model = TaskModel::class;
}
