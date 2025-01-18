<?php

namespace App\Models;

use App\Traits\Aliasable;
use Illuminate\Database\Eloquent\Model;

class TaskCategory extends Model
{
    use Aliasable;
    protected $fillable = ['name', 'description'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
