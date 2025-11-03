<?php

namespace App\DataAccess\Remote;

use App\DataAccess\RateTypeDataAccess;
use App\Models\RateType as RateTypeModel;

class RateType extends BaseRemoteAccess implements RateTypeDataAccess
{
    protected string $model = RateTypeModel::class;
}
