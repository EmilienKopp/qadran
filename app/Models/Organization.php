<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(OrganizationUser::class)
            ->withPivot('elevated')
            ->withTimestamps();
    }
}
