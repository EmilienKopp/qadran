<?php

namespace App\Domain\Project;

use App\Models\Organization;
use App\Models\Project;
use Illuminate\Support\Collection;
use Date;

class ProjectActivitySummary {
  public Project $project;
  public Organization $organization;
  public Collection $users;
  public Collection $entries;
  public Collection $activityLogs;

  public function __construct(Project $project) {
    $this->project = $project->load('organization', 'users', 'entries.activityLogs');
    $this->organization = $project->organization;
    $this->users = $project->users;
    $this->entries = $project->entries;
  }

  public function getActivityLogs($cutoffDate = null): Collection {
    $cutoffDate = $cutoffDate ? Date::make($cutoffDate) : now();
    return $this->entries
      ->where('out', '<=', $cutoffDate)
      ->pluck('activityLogs')
      ->flatten();
  }


}