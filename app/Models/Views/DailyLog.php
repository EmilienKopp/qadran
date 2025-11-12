<?php

namespace App\Models\Views;

use App\Models\Activity;
use App\Models\ClockEntry;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Carbon\Carbon;

/**
 * DailyLog View Model
 * 
 * This model represents the daily_logs_view database view which aggregates
 * clock entries by user, project, and date.
 * 
 * Data Structure:
 * - The view aggregates clock entries into daily summaries
 * - activities() fetches Activity records (time breakdown by task category)
 * - timeLogs() fetches ClockEntry records (actual clock in/out times)
 * 
 * Usage:
 * - getDaily($date): Get all daily logs for a specific date with related data
 * - getMonthly($date): Get all daily logs for a month with related data
 * 
 * Related Models:
 * - Activity: Stores the breakdown of time by task category for each day/project
 * - ClockEntry: Stores the actual clock in/out times
 * - TaskCategory: Categories for activities (e.g., "Development", "Meeting")
 */
class DailyLog extends ReadOnlyModel
{
    use UsesTenantConnection;

    protected $table = 'daily_logs_view';

    protected $casts = [
        'date' => 'date',
        'total_seconds' => 'integer',
        'total_minutes' => 'float',
    ];

    /**
     * Get activities for this daily log
     */
    public function activities()
    {
        return Activity::where('user_id', $this->user_id)
            ->where('project_id', $this->project_id)
            ->where('date', $this->date)
            ->with('taskCategory')
            ->get();
    }

    /**
     * Get time logs (clock entries) for this daily log
     */
    public function timeLogs()
    {
        return ClockEntry::where('user_id', $this->user_id)
            ->where('project_id', $this->project_id)
            ->whereDate('in', $this->date)
            ->orderBy('in', 'asc')
            ->get();
    }

    /**
     * Get daily logs for a specific date with related data
     */
    public static function getDaily($date)
    {
        return static::where('user_id', auth()->user()->id)
            ->where('date', $date)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($dailyLog) {
                // Fetch related data
                $dailyLog->activities = $dailyLog->activities();
                $dailyLog->timeLogs = $dailyLog->timeLogs();
                
                // Ensure total_seconds is set
                if (!$dailyLog->total_seconds && $dailyLog->timeLogs->isNotEmpty()) {
                    $dailyLog->total_seconds = $dailyLog->timeLogs->sum('duration_seconds');
                }
                
                return $dailyLog;
            });
    }

    /**
     * Get monthly logs for a specific date range
     */
    public static function getMonthly($date)
    {
        $startOfMonth = Carbon::parse($date)->startOfMonth();
        $endOfMonth = Carbon::parse($date)->endOfMonth();

        return static::where('user_id', auth()->user()->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($dailyLog) {
                $dailyLog->activities = $dailyLog->activities();
                $dailyLog->timeLogs = $dailyLog->timeLogs();
                
                if (!$dailyLog->total_seconds && $dailyLog->timeLogs->isNotEmpty()) {
                    $dailyLog->total_seconds = $dailyLog->timeLogs->sum('duration_seconds');
                }
                
                return $dailyLog;
            });
    }
}
