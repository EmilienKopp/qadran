<?php

namespace App\Enums;

use App\Traits\ExtendEnums;

enum ExpenseStatus: string
{
    use ExtendEnums;

    case Draft = 'draft';
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Paid = 'paid';
}
