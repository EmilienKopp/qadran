<?php

namespace App\DataAccess\Remote;

use App\DataAccess\ClockEntryDataAccess;
use App\Models\ClockEntry as ClockEntryModel;

class ClockEntry extends BaseRemoteAccess implements ClockEntryDataAccess
{
    protected string $model = ClockEntryModel::class;
}
