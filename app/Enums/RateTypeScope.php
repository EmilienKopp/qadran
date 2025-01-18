<?php

namespace App\Enums;

use App\Traits\ExtendEnums;

enum RateTypeScope: string
{
    use ExtendEnums;
    case Organization = 'organization';
    case Project = 'project';
    case User = 'user';
}
