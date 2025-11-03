<?php

namespace App\DataAccess\Remote;

use App\DataAccess\TagDataAccess;
use App\Models\Tag as TagModel;

class Tag extends BaseRemoteAccess implements TagDataAccess
{
    protected string $model = TagModel::class;
}
