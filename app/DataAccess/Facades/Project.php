<?php

namespace App\DataAccess\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed find($id)
 * @method static mixed firstWhere($column, $operator, $value = null)
 *
 * @see \App\DataAccess\{ProjectDataAccess}
 */
class Project extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\DataAccess\ProjectDataAccess::class;
    }
}
