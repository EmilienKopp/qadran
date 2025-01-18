<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClockEntry extends Model
{
    /** @use HasFactory<\Database\Factories\ClockEntryFactory> */
    use HasFactory;

    protected $fillable = [
        'in',
        'out',
        'note',
        'project_id',
        'user_id',
    ];
}
