<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class ActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityLogFactory> */
    use HasFactory, UsesTenantConnection;

    protected $fillable = [
        'clock_entry_id',
        'activity_type_id',
        'task_id',
        'start_offset_seconds',
        'end_offset_seconds',
        'notes',
    ];

    public function clockEntry()
    {
        return $this->belongsTo(ClockEntry::class);
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
