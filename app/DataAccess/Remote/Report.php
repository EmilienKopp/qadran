<?php

namespace App\DataAccess\Remote;

use App\DataAccess\{ReportDataAccess};

class Report extends BaseRemoteAccess implements ReportDataAccess
{
    protected string $resourceEndpoint = 'api/reports';
}
