<?php

namespace App\DataAccess\Remote;

use App\DataAccess\IndustryDataAccess;
use App\Models\Industry as IndustryModel;

class Industry extends BaseRemoteAccess implements IndustryDataAccess
{
    protected string $model = IndustryModel::class;
}
