<?php

namespace App\Enums;

use App\Traits\ExtendEnums;

enum TaskPriority: int
{
    use ExtendEnums;
    case None = 0;
    case Low = 1;
    case Medium = 2;
    case High = 3;
    case Urgent = 4;
    case Critical = 5;
    case LifeThreatening = 6;
    case Blocker = -1;
}
