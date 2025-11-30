<?php

namespace App\Repositories\Local;

use App\Models\ClockEntry;
use App\Repositories\ClockEntryRepositoryInterface;
use Illuminate\Support\Collection;

class LocalClockEntryRepository implements ClockEntryRepositoryInterface
{
    public function find(int $id): ?ClockEntry
    {
        return ClockEntry::find($id);
    }

    public function all(): Collection
    {
        return ClockEntry::all();
    }

    public function findByUser(int $userId): Collection
    {
        return ClockEntry::where('user_id', $userId)->get();
    }

    public function findActiveByUser(int $userId): ?ClockEntry
    {
        return ClockEntry::where('user_id', $userId)
            ->whereNull('end_time')
            ->first();
    }

    public function create(array $data): ClockEntry
    {
        return ClockEntry::create($data);
    }

    public function update(int $id, array $data): ?ClockEntry
    {
        $entry = ClockEntry::find($id);
        if (! $entry) {
            return null;
        }
        $entry->update($data);

        return $entry->fresh();
    }

    public function delete(int $id): bool
    {
        $entry = ClockEntry::find($id);
        if (! $entry) {
            return false;
        }

        return $entry->delete();
    }
}
