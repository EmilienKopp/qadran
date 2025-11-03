<?php

namespace App\DataAccess\Local;

use App\DataAccess\ClockEntryDataAccess;
use App\Models\ClockEntry as ClockEntryModel;

class ClockEntry extends BaseLocalAccess implements ClockEntryDataAccess
{
    protected string $model = ClockEntryModel::class;
}
