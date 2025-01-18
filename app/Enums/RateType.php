<?php

namespace App\Enums;

use App\Traits\ExtendEnums;

enum RateType: string
{
    use ExtendEnums;

    case Standard = 'standard';
    case Overtime = 'overtime';
    case Holiday = 'holiday';
    case Special = 'special';
    case Custom = 'custom';
}
