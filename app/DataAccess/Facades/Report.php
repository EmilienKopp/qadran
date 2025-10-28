<?php

namespace App\DataAccess\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed find($id)
 * @method static mixed firstWhere($column, $operator, $value = null)
 *
 * @see \App\DataAccess\{ReportDataAccess}
 */
class Report extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\DataAccess\ReportDataAccess::class;
    }
}
