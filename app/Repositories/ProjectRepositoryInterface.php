<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int $id): ?Project;
    public function all(): Collection;
    public function findByOrganization(int $organizationId): Collection;
    public function create(array $data): Project;
    public function update(int $id, array $data): ?Project;
    public function delete(int $id): bool;
}
