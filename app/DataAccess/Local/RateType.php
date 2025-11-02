<?php

namespace App\DataAccess\Local;

use App\DataAccess\RateTypeDataAccess;
use App\Models\RateType as RateTypeModel;

class RateType extends BaseLocalAccess implements RateTypeDataAccess
{
    protected string $model = RateTypeModel::class;
}
