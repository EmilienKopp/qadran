<?php

namespace App\DataAccess\Remote;

use App\DataAccess\TaskCategoryDataAccess;
use App\Models\TaskCategory as TaskCategoryModel;

class TaskCategory extends BaseRemoteAccess implements TaskCategoryDataAccess
{
    protected string $model = TaskCategoryModel::class;
}
