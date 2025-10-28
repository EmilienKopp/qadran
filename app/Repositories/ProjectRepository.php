<?php

namespace App\Repositories;

use Splitstack\Stashable\Traits\Stashable;
use Splitstack\Stashable\Attributes\WithCache;

const DEFAULT_TTL = 60;

class ProjectRepository extends Repository {
  protected static $model = Project::class;
  protected static array $with = ['entries'];

  public static function getForUser()
  {
    $projects = auth()->user()->projects->load('organization');
    return $projects;
  }
}