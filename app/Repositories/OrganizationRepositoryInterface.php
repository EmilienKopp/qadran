<?php

namespace App\Repositories;

use App\Models\Organization;
use Illuminate\Support\Collection;

interface OrganizationRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int $id): ?Organization;
    public function all(): Collection;
    public function findByUser(int $userId): Collection;
    public function create(array $data): Organization;
    public function update(int $id, array $data): ?Organization;
    public function delete(int $id): bool;
}
