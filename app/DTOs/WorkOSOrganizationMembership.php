<?php

namespace App\DTOs;

use WorkOS\Resource\OrganizationMembership;

class WorkOSOrganizationMembership
{
  public string $id;
  public string $organizationId;
  public string $userId;
  public $role;
  public $roles;
  public string $organizationName;
  public string $createdAt;
  public string $updatedAt;

  public function __construct(
    OrganizationMembership $membership,
  ) {
    $this->id = $membership->id;
    $this->organizationId = $membership->organization_id;
    $this->userId = $membership->user_id;
    $this->role = $membership->role;
    $this->roles = $membership->roles;
    $this->organizationName = $membership->organization_name;
    $this->createdAt = $membership->created_at;
    $this->updatedAt = $membership->updated_at;
  }
}