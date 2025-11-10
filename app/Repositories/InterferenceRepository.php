<?php

namespace App\Repositories;

use App\Models\Interference;
use Carbon\Carbon;
use DateTimeInterface;

class InterferenceRepository extends Repository
{
    protected static $model = Interference::class;
    protected static array $with = ['project', 'clockEntry'];

    /**
     * Register a new interference
     *
     * @param array $data Should contain: user_id, in, out, and optionally project_id, clock_entry_id, timezone, notes
     * @return Interference
     */
    public static function register(array $data): Interference
    {
        $userId = $data['user_id'];
        $inTime = $data['in'] ?? now();
        $outTime = $data['out'] ?? now();
        $timezone = $data['timezone'] ?? auth('tenant')->user()->timezone ?? config('app.timezone');

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

    /**
     * Get all interferences for a user, optionally filtered by date range
     *
     * @param int $userId
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getInterferencesForUser(
        int $userId,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ) {
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

    /**
     * Get today's interferences for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTodayInterferences(int $userId)
    {
        return Interference::where('user_id', $userId)
            ->whereDate('in', now())
            ->with('project', 'clockEntry')
            ->orderBy('in', 'desc')
            ->get();
    }

    /**
     * Delete an interference
     *
     * @param Interference $interference
     * @return bool
     */
    public static function delete(Interference $interference): bool
    {
        return $interference->delete();
    }
}
