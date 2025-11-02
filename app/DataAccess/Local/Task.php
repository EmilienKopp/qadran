<?php

namespace App\DataAccess\Local;

use App\DataAccess\TaskDataAccess;
use App\Models\Task as TaskModel;

class Task extends BaseLocalAccess implements TaskDataAccess
{
    protected string $model = TaskModel::class;
}
