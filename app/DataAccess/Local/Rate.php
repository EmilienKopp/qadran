<?php

namespace App\DataAccess\Local;

use App\DataAccess\RateDataAccess;
use App\Models\Rate as RateModel;

class Rate extends BaseLocalAccess implements RateDataAccess
{
    protected string $model = RateModel::class;
}
