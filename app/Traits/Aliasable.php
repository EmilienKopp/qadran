<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait Aliasable
{
    protected $aliasable = 'name';
    protected $aliasTable;
    protected $aliasForeignKey;
    protected $scopes = [];
    protected $ttlSeconds = 300;

    public function alias(?array $scopes = null)
    {
        return Cache::remember(
            $this->getAliasCacheKey($scopes),
            now()->addSeconds($this->ttlSeconds),
            function () use ($scopes) {
                return $this->getAlias($scopes);
            }
        ) ?? $this->{$this->aliasable};
    }

    public function aliases()
    {
        return $this->hasMany($this->getAliasModel(), $this->getAliasForeignKey());
    }

    public function getAliasTable()
    {
        if (!isset($this->aliasTable)) {
            $this->aliasTable = $this->getTable() . '_aliases';
        }
        return $this->aliasTable;
    }

    public function getAlias(?array $scopes)
    {
        $query = DB::table($this->getAliasTable())
            ->where($this->getAliasForeignKey(), $this->id);

        $scopes = $scopes ?? array_merge($this->scopes, [
            'organization_id' => Auth::user()->organization_id ?? null,
            'user_id' => Auth::id(),
        ]);

        foreach ($scopes as $column => $value) {
            if(isset($value)) {
                $query->where($column, $value);
            }
        }

        return $query->value('alias');
    }

    public function getAliasForeignKey()
    {
        return $this->getTable() . '_id';
    }

    public function setScopes(array $scopes)
    {
        $this->scopes = $scopes;
    }

    public function getAliasCacheKey(?array $scopes)
    {
        $scopes = $scopes ?? $this->scopes;
        $key = $this->getAliasTable() . '.' . $this->getAliasForeignKey() . '.' . $this->id;
        foreach ($scopes as $column => $value) {
            $key .= '.' . $column . '.' . $value;
        }
        return $key;
    }

    public function getAliasModel()
    {
        $modelClass = 'App\\Models\\' . Str::singular(Str::studly($this->getAliasTable()));
        if (!class_exists($modelClass)) {
            throw new \Exception('Alias model does not exist: ' . $modelClass);
        }
        return $modelClass;
    }
}
