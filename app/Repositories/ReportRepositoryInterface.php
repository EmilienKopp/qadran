<?php

namespace App\Repositories;

use App\Models\Report;
use Illuminate\Support\Collection;

interface ReportRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int $id): ?Report;
    public function all(): Collection;
    public function findByProject(int $projectId): Collection;
    public function create(array $data): Report;
    public function update(int $id, array $data): ?Report;
    public function delete(int $id): bool;
}
