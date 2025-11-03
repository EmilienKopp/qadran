<?php

namespace App\DataAccess\Remote;

use App\DataAccess\VoiceCommandDataAccess;
use App\Models\VoiceCommand as VoiceCommandModel;

class VoiceCommand extends BaseRemoteAccess implements VoiceCommandDataAccess
{
    protected string $model = VoiceCommandModel::class;
}
