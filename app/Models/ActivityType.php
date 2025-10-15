<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class ActivityType extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = ['name', 'description'];

    public function activities()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
