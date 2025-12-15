<?php


namespace App\Services;

use App\DTOs\WorkOSOrganizationMembership;


class WorkOSService
{
  public function __construct(
    public \WorkOS\UserManagement $usersApi,
  )
  {
  }

  public function getUserOrganizationMemberships(string $workosUserId): array
  {
    [$_before, $_after, $users] = $this->usersApi->listOrganizationMemberships($workosUserId);
    return collect($users)
      ->mapInto(WorkOSOrganizationMembership::class)
      ->toArray();
  }
}