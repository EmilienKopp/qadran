<?php

namespace App\DataAccess\Local;

use App\DataAccess\TagDataAccess;
use App\Models\Tag as TagModel;

class Tag extends BaseLocalAccess implements TagDataAccess
{
    protected string $model = TagModel::class;
}
