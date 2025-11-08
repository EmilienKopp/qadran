<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasGitHubConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasGitHubConnection, UsesTenantConnection,
        HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'handle',
        'title',
        'phone_main',
        'phone_secondary',
        'github',
        'email',
        'password',
        'workos_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function booted()
    {
        static::creating(function ($user) {
            if(!$user->handle) {
                $user->handle = $user->email;
            }
            $user->assignRole('user');
        });
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class)
            ->using(OrganizationUser::class)
            ->withPivot('elevated')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Project>
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->using(ProjectUser::class);
    }

    public function clockEntries()
    {
        return $this->hasMany(ClockEntry::class)->with('project');
    }

    public function todaysEntries()
    {
        return $this->clockEntries()->today()->orderBy('created_at', 'desc');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function voiceCommands()
    {
        return $this->hasMany(VoiceCommand::class);
    }
}
