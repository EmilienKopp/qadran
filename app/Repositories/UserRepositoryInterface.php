<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int $id, ?array $relations = null): ?User;

    public function findByWorkosId(string $workosId): ?User;

    public function findByGitHubId(string $githubUserId): ?User;

    public function findByGoogleId(string $googleUserId): ?User;

    public function findByEmail(string $email): ?User;

    public function getGitHubUserId(int $userId): ?string;

    public function all(): Collection;

    public function create(array $data): User;

    public function update(int $id, array $data): ?User;

    public function delete(int $id): bool;
}
