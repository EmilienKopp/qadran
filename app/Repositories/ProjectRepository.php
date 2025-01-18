<?php

namespace App\Repositories;

class ProjectRepository extends Repository {
  protected static $model = Project::class;
  protected static array $with = ['entries'];
}