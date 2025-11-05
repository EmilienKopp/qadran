<?php

namespace App\Models\Views;

use App\Traits\CanSortPaginatedWithCallback;
use Illuminate\Database\Eloquent\Model;

/**
 * A base model for read-only database views.
 * Prevents direct writes, but allows updates via a proxied model class.
 *
 * @important for the proxying to work, the proxied model must have the same primary key type and name (id, string/char)
 */
abstract class ReadOnlyModel extends Model
{
    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'char';

    protected $fillable = [];

    public $timestamps = false;

    protected $casts = [
        'id' => 'string',
    ];

    /**
     * The model class to proxy write operations to.
     * Must be defined in child classes.
     */
    protected static $proxiedModelClass = null;

    public function delete()
    {
        throw new \Exception('Cannot delete from read-only model');
    }

    public function update(array $attributes = [], array $options = [])
    {
        $proxiedModel = $this->getProxiedModelInstance();
        $existingRecord = $proxiedModel->find($this->getKey());
        if (! $existingRecord) {
            throw new \Exception('Record does not exist in proxied model.');
        }
        $existingRecord->update($attributes, $options);

        return static::find($this->getKey());
    }

    public static function create(array $attributes = [])
    {
        return static::getProxiedModelClass()::create($attributes);
    }

    public function save(array $options = [])
    {
        throw new \Exception('Cannot save read-only model directly. Define a "proxiedModelClass" and use update() instead.');
    }

    /**
     * Get an instance of the proxied model.
     */
    private function getProxiedModelInstance(): Model
    {
        $modelClass = static::getProxiedModelClass();

        return new $modelClass;
    }

    /**
     * Hydrate an instance of the proxied model with attributes from this read-only model
     * without a database query.
     */
    public function underlying($forceFetch = false)
    {
        if ($forceFetch) {
            $modelClass = static::getProxiedModelClass();

            return $modelClass::find($this->getKey());
        }
        $instance = new static::$proxiedModelClass;
        $attributes = array_intersect_key($this->attributesToArray(), array_flip($instance->getFillable()));
        $instance = $instance->newInstance($attributes, exists: true);
        $instance->wasRecentlyCreated = false;

        return $instance;
    }

    /**
     * Get the proxied model class name.
     */
    private static function getProxiedModelClass(): string
    {
        if (! static::$proxiedModelClass) {
            throw new \Exception('Proxied model class not defined. Set $proxiedModelClass in your model.');
        }

        if (! class_exists(static::$proxiedModelClass)) {
            throw new \Exception("Proxied model class '".static::$proxiedModelClass."' does not exist.");
        }

        return static::$proxiedModelClass;
    }

    /**
     * Get attributes filtered to only those fillable on the proxied model.
     */
    private function getFilteredAttributes(Model $proxiedModel): array
    {
        $fillables = $proxiedModel->getFillable();

        return collect($this->getAttributes())
            ->only($fillables)
            ->toArray();
    }
}
