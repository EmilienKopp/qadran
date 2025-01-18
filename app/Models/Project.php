<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $guarded = [];

    public function entries()
    {
        return $this->hasMany(ClockEntry::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(ProjectUser::class)
            ->withPivot('roles');
    }
}
