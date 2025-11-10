<?php

namespace App\Repositories\Local;

use App\Models\Interference;
use App\Repositories\InterferenceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LocalInterferenceRepository implements InterferenceRepositoryInterface
{
    public function find(int $id): ?Interference
    {
        return Interference::find($id);
    }

    public function all(): Collection
    {
        return Interference::all();
    }

    public function findByUser(int $userId): Collection
    {
        return Interference::where('user_id', $userId)->get();
    }

    public function register(array $data): Interference
    {
        $userId = $data['user_id'];
        $inTime = $data['in'] ?? now();
        $outTime = $data['out'] ?? now();
        $timezone = $data['timezone'] ?? auth('tenant')->user()?->timezone ?? config('app.timezone');

        // Parse the times
        $inTime = $inTime instanceof Carbon
            ? $inTime
            : Carbon::parse($inTime);

        $outTime = $outTime instanceof Carbon
            ? $outTime
            : Carbon::parse($outTime);

        return Interference::create([
            'user_id' => $userId,
            'project_id' => $data['project_id'] ?? null,
            'clock_entry_id' => $data['clock_entry_id'] ?? null,
            'in' => $inTime,
            'out' => $outTime,
            'timezone' => $timezone,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function getInterferencesForUser(
        int $userId,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ): Collection {
        $query = Interference::where('user_id', $userId)
            ->with('project', 'clockEntry')
            ->orderBy('in', 'desc');

        if ($startDate) {
            $query->where('in', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('in', '<=', $endDate);
        }

        return $query->get();
    }

    public function delete(int $id): bool
    {
        $interference = Interference::find($id);
        if (!$interference) {
            return false;
        }
        return $interference->delete();
    }
}
