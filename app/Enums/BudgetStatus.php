<?php

namespace App\Enums;

use App\Traits\ExtendEnums;

enum BudgetStatus: string
{
    use ExtendEnums;

    case Draft = 'draft';
    case Approved = 'approved';
    case Active = 'active';
    case Exhausted = 'exhausted';
    case Cancelled = 'cancelled';
    
}
