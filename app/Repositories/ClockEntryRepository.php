<?php

namespace App\Repositories;

use App\Models\ClockEntry;
use App\Models\Project;
use Carbon\Carbon;
use DateTimeInterface;

class ClockEntryRepository extends Repository
{
    protected static $model = ClockEntry::class;
    protected static array $with = ['project'];

    /**
     * Get the active (unclosed) clock entry for a user
     *
     * @param int $userId
     * @return ClockEntry|null
     */
    public static function getActiveEntry(int $userId): ?ClockEntry
    {
        return ClockEntry::where('user_id', $userId)
            ->whereNull('out')
            ->orderBy('in', 'desc')
            ->first();
    }

    /**
     * Clock in to a project
     *
     * @param array $data Should contain: user_id, project_id, in (optional), timezone (optional)
     * @return ClockEntry
     */
    public static function clockIn(array $data): ClockEntry
    {
        $userId = $data['user_id'];
        $projectId = $data['project_id'];
        $clockInTime = $data['in'] ?? now();
        $timezone = $data['timezone'] ?? auth('tenant')->user()->timezone ?? config('app.timezone');

        // Parse the clock in time
        $clockInTime = $clockInTime instanceof Carbon
            ? $clockInTime
            : now()->parse($clockInTime);

        // Get any active entry
        $activeEntry = static::getActiveEntry($userId);

        // If there's an active entry for a different project, close it first
        if ($activeEntry && $activeEntry->project_id !== $projectId) {
            $activeEntry->update(['out' => $clockInTime]);
            $activeEntry = null; // Reset so we create a new entry
        }

        // If no active entry (or we just closed it), create a new one
        if (!$activeEntry) {
            return ClockEntry::create([
                'user_id' => $userId,
                'project_id' => $projectId,
                'in' => $clockInTime,
                'timezone' => $timezone,
                'notes' => $data['note'] ?? null,
            ]);
        }

        // If there's an active entry for the same project, just return it
        return $activeEntry;
    }

    /**
     * Clock out of the current active entry
     *
     * @param int $userId
     * @param DateTimeInterface|string|null $clockOutTime
     * @return ClockEntry|null
     */
    public static function clockOut(int $userId, DateTimeInterface|string $clockOutTime = null): ?ClockEntry
    {
        $activeEntry = static::getActiveEntry($userId);

        if (!$activeEntry) {
            return null;
        }

        $clockOutTime = $clockOutTime instanceof Carbon
            ? $clockOutTime
            : now()->parse($clockOutTime ?? now());

        $activeEntry->update(['out' => $clockOutTime]);

        return $activeEntry->fresh();
    }

    /**
     * Update a clock entry with clock out time
     *
     * @param ClockEntry $entry
     * @param array $data Should contain: out, and optionally note
     * @return ClockEntry
     */
    public static function updateClockOut(ClockEntry $entry, array $data): ClockEntry
    {
        $clockOutTime = $data['out'] ?? now();

        $clockOutTime = $clockOutTime instanceof Carbon
            ? $clockOutTime
            : now()->parse($clockOutTime);

        $entry->update([
            'out' => $clockOutTime,
            'note' => $data['note'] ?? $entry->note,
        ]);

        return $entry->fresh();
    }

    /**
     * Get all clock entries for a user, optionally filtered by date range
     *
     * @param int $userId
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getEntriesForUser(
        int $userId,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ) {
        $query = ClockEntry::where('user_id', $userId)
            ->with('project')
            ->orderBy('in', 'desc');

        if ($startDate) {
            $query->where('in', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('in', '<=', $endDate);
        }

        return $query->get();
    }

    /**
     * Get today's clock entries for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTodayEntries(int $userId)
    {
        return ClockEntry::where('user_id', $userId)
            ->whereDate('in', now())
            ->with('project')
            ->orderBy('in', 'desc')
            ->get();
    }

    /**
     * Delete a clock entry
     *
     * @param ClockEntry $entry
     * @return bool
     */
    public static function delete(ClockEntry $entry): bool
    {
        return $entry->delete();
    }
}
