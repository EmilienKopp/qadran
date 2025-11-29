<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface ActivityLogRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int $id): mixed;

    public function all(): Collection;

    public function findByClockEntry(int $clockEntryId): Collection;

    public function findByUserAndDate(int $userId, string $date): Collection;

    public function sync(array $validated): void;

    public function create(array $data): mixed;

    public function update(int $id, array $data): mixed;

    public function delete(int $id): bool;
}
