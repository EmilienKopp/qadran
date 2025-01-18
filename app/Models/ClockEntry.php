<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function scopeToday($query)
    {
        return $query->whereDate('in', now());
    }

    public function scopeOwn($query, $userOrId = null)
    {
        if(!$userOrId) {
            $userOrId = Auth::user();
        } else if ($userOrId instanceof User) {
            $userOrId = $userOrId->id;
        }
        return $query->where('user_id', $userOrId);
    }
}
