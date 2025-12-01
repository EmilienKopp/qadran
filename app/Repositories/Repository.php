<?php

namespace App\Repositories;

abstract class Repository
{
    protected static array $with = [];

    protected static int $cacheTTL = 60 * 60; // 1 hour

    protected static $model;

    protected static ?string $search;

    protected static function cacheKey(string $key): string
    {
        return static::$model.'.'.md5($key);
    }

    /**
     * Us to add clauses like with and join to the query
     *
     * @param  mixed  $query
     * @return void
     */
    protected static function ftsWith($query) {}

    protected static function baseSearch(?string $search, $onlyTrashed = false, $paginateAt = null, $filters = [])
    {
        $query = static::$model::query();
        static::$search = $search;

        if (empty($search)) {
            $query = static::$model::with(static::$with);
        } else {

            $query = static::$model::search($search)->query(function ($query) use ($onlyTrashed) {
                if ($onlyTrashed) {
                    $query->onlyTrashed();
                }
                static::ftsWith($query);
            });
        }
        foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }

        return static::maybePaginate($query, $paginateAt);
    }

    protected static function maybePaginate($query, $paginateAt)
    {
        if (! $paginateAt) {
            return $query->get();
        }

        return empty(static::$search) ? $query->paginate($paginateAt) : $query->paginateWithQueryString($paginateAt);
    }
}
