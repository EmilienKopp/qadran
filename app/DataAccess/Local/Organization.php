<?php

namespace App\DataAccess\Local;

use App\DataAccess\OrganizationDataAccess;
use App\Models\Organization as OrganizationModel;

class Organization extends BaseLocalAccess implements OrganizationDataAccess
{
    protected string $model = OrganizationModel::class;
}
