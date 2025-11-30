<?php

namespace App\DataAccess\Local;

use App\DataAccess\ReportDataAccess;
use App\Models\Report as ReportModel;

class Report extends BaseLocalAccess implements ReportDataAccess
{
    protected string $model = ReportModel::class;
}
