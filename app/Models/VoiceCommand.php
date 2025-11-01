<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class VoiceCommand extends Model
{
    use UsesTenantConnection;
    protected $fillable = [
        'user_id',
        'transcript',
        'parsed_command',
        'metadata',
    ];

    protected $casts = [
        'parsed_command' => 'array',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
