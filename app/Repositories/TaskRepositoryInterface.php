<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int $id): ?Task;

    public function all(): Collection;

    public function findByProject(int $projectId): Collection;

    public function create(array $data): Task;

    public function update(int $id, array $data): ?Task;

    public function delete(int $id): bool;
}
