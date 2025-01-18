<?php

namespace App\Enums;

use App\Traits\ExtendEnums;

enum RoleEnum: string
{
    use ExtendEnums;
    case Admin = 'admin';
    case Freelancer = 'freelancer';
    case Employer = 'employer';
    case Employee = 'employee';
    case User = 'user';

}
