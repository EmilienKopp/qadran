<?php

namespace App\Enums;

use App\Traits\ExtendEnums;

enum RateFrequency: string
{
    use ExtendEnums;

    case Hourly = 'hourly';
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Project = 'project';
    case Fixed = 'fixed';

}
