<?php

namespace App\Repositories\Local;

use App\Models\ActivityLog;
use App\Repositories\ActivityLogRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LocalActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function find(int $id): ?ActivityLog
    {
        return ActivityLog::find($id);
    }

    public function all(): Collection
    {
        return ActivityLog::all();
    }

    public function findByClockEntry(int $clockEntryId): Collection
    {
        return ActivityLog::where('clock_entry_id', $clockEntryId)->get();
    }

    public function findByUserAndDate(int $userId, string $date): Collection
    {
        return ActivityLog::with(['clockEntry', 'activityType', 'task'])
            ->whereHas('clockEntry', function ($query) use ($date, $userId) {
                $query->where('user_id', $userId)
                    ->whereBetween('in', [\Carbon\Carbon::parse($date)->startOfDay(), \Carbon\Carbon::parse($date)->endOfDay()]);
            })
            ->get();
    }

    public function sync(array $validated): void
    {
        DB::transaction(function () use ($validated) {
            for ($i = 0; $i < count($validated['activity_type_id']); $i++) {
                $hours = $validated['hours'][$i] ?? 0;
                $minutes = $validated['minutes'][$i] ?? 0;
                $activityLogId = $validated['id'][$i] ?? null;

                if ($hours === 0 && $minutes === 0) {
                    continue;
                }

                $totalMinutes = ($hours * 60) + $minutes;
                $start_offset_seconds = 0; // Not handling offset properly yet, to come in future versions
                $end_offset_seconds = $start_offset_seconds + ($totalMinutes * 60);

                $data = [
                    'clock_entry_id' => $validated['clock_entry_id'] ?? null,
                    'start_offset_seconds' => $start_offset_seconds,
                    'end_offset_seconds' => $end_offset_seconds,
                    'activity_type_id' => $validated['activity_type_id'][$i] ?? null,
                ];

                if ($activityLogId) {
                    $data['id'] = $activityLogId;
                }

                ActivityLog::upsert($data, ['id'], [
                    'start_offset_seconds',
                    'end_offset_seconds',
                    'activity_type_id',
                ]);
            }

            if (! empty($validated['deleted'])) {
                ActivityLog::whereIn('id', $validated['deleted'])->delete();
            }
        });
    }

    public function create(array $data): ActivityLog
    {
        return ActivityLog::create($data);
    }

    public function update(int $id, array $data): ?ActivityLog
    {
        $activityLog = ActivityLog::find($id);
        if (! $activityLog) {
            return null;
        }
        $activityLog->update($data);

        return $activityLog->fresh();
    }

    public function delete(int $id): bool
    {
        $activityLog = ActivityLog::find($id);
        if (! $activityLog) {
            return false;
        }

        return $activityLog->delete();
    }
}
