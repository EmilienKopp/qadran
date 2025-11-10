<?php

namespace App\Repositories;

use App\Models\Interference;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface InterferenceRepositoryInterface
{
    /**
     * Find an interference by ID
     */
    public function find(int $id): ?Interference;

    /**
     * Get all interferences
     */
    public function all(): Collection;

    /**
     * Find interferences by user
     */
    public function findByUser(int $userId): Collection;

    /**
     * Register a new interference
     *
     * @param array $data Should contain: user_id, in, out, and optionally project_id, clock_entry_id, timezone, notes
     * @return Interference
     */
    public function register(array $data): Interference;

    /**
     * Get all interferences for a user, optionally filtered by date range
     *
     * @param int $userId
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return Collection
     */
    public function getInterferencesForUser(
        int $userId,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ): Collection;

    /**
     * Delete an interference
     */
    public function delete(int $id): bool;
}
