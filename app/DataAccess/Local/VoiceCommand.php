<?php

namespace App\DataAccess\Local;

use App\DataAccess\VoiceCommandDataAccess;
use App\Models\VoiceCommand as VoiceCommandModel;

class VoiceCommand extends BaseLocalAccess implements VoiceCommandDataAccess
{
    protected string $model = VoiceCommandModel::class;
}
