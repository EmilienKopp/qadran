<?php

namespace App\DataAccess\Local;

use App\DataAccess\TaskCategoryDataAccess;
use App\Models\TaskCategory as TaskCategoryModel;

class TaskCategory extends BaseLocalAccess implements TaskCategoryDataAccess
{
    protected string $model = TaskCategoryModel::class;
}
