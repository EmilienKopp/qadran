<?php

namespace App\Models;

use Carbon\Carbon;
use EmilienKopp\DatesFormatter\FormatsDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ClockEntry extends Model
{
    /** @use HasFactory<\Database\Factories\ClockEntryFactory> */
    use HasFactory, FormatsDates;

    protected $fillable = [
        'in',
        'out',
        'note',
        'project_id',
        'user_id',
        'timezone',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('in', now());
    }

    public function scopeOwn($query, $userOrId = null)
    {
        if (!$userOrId) {
            $userOrId = Auth::user();
        } else if ($userOrId instanceof User) {
            $userOrId = $userOrId->id;
        }
        return $query->where('user_id', $userOrId);
    }

    protected function inTime(): Attribute
    {
        return Attribute::make(
            get: fn($value) => 
                Carbon::parse($value)
                    ->setTimezone($this->timezone)
                    ->format('H:i:s'),
        );
    }

    protected function outTime(): Attribute
    {
        return Attribute::make(
            get: fn($value) => 
                Carbon::parse($value)
                    ->setTimezone($this->timezone)
                    ->format('H:i:s'),
        );
    }

    protected function in(): Attribute
    {
        return Attribute::make(
            get: fn($value) => 
                isset($value) 
                ? Carbon::parse($value)
                    ->setTimezone($this->timezone)
                    ->format('Y-m-d H:i:s') 
                : null,
        );
    }

    protected function out(): Attribute
    {
        return Attribute::make(
            get: fn($value) => 
                isset($value) 
                    ? Carbon::parse($value)
                        ->setTimezone($this->timezone)
                        ->format('Y-m-d H:i:s') 
                    : null,
        );
    }

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn($value) => 
                Carbon::parse($value)
                    ->setTimezone($this->timezone)
                    ->format('Y-m-d'),
        );
    }
}
