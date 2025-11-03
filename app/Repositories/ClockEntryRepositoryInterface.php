<?php

namespace App\Repositories;

use App\Models\ClockEntry;
use Illuminate\Support\Collection;

interface ClockEntryRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int $id): ?ClockEntry;
    public function all(): Collection;
    public function findByUser(int $userId): Collection;
    public function findActiveByUser(int $userId): ?ClockEntry;
    public function create(array $data): ClockEntry;
    public function update(int $id, array $data): ?ClockEntry;
    public function delete(int $id): bool;
}
