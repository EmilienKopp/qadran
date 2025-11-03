<?php

namespace App\DataAccess\Local;

use App\DataAccess\IndustryDataAccess;
use App\Models\Industry as IndustryModel;

class Industry extends BaseLocalAccess implements IndustryDataAccess
{
    protected string $model = IndustryModel::class;
}
