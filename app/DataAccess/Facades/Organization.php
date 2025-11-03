<?php

namespace App\DataAccess\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed find($id)
 * @method static mixed firstWhere($column, $operator, $value = null)
 *
 * @see \App\DataAccess\{OrganizationDataAccess}
 */
class Organization extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\DataAccess\OrganizationDataAccess::class;
    }
}
