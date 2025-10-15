<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class ActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityLogFactory> */
    use HasFactory, UsesTenantConnection;

    protected $fillable = ['activity_type_id', 'user_id', 'description'];

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }
}
