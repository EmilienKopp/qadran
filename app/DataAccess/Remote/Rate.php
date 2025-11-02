<?php

namespace App\DataAccess\Remote;

use App\DataAccess\RateDataAccess;
use App\Models\Rate as RateModel;

class Rate extends BaseRemoteAccess implements RateDataAccess
{
    protected string $model = RateModel::class;
}
