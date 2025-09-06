<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepositorySettings extends Model
{
    protected $fillable = [
        'repository_id',
        'repository',
        'branch',
        'excluded_folders',
        'excluded_extensions',
        'include_diff_by_default',
    ];

    protected $casts = [
        'excluded_folders' => 'array',
        'excluded_extensions' => 'array',
        'include_diff_by_default' => 'boolean',
    ];
}