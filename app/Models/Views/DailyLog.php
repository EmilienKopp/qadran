<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Carbon\Carbon;

class DailyLog extends ReadOnlyModel
{
    use UsesTenantConnection;

    protected $table = 'daily_logs_view';

    public static function getDaily($date)
    {
        return static::where('user_id', auth()->user()->id)
            ->where('date', $date)
            ->orderBy('date', 'desc')
            ->get()
            ->transform(function ($dailyLog) {
                $dailyLog->activities = $dailyLog->activities();
                $dailyLog->timeLogs = $dailyLog->timeLogs();
                if (!$dailyLog->total_seconds) {
                    $dailyLog->total_seconds = Carbon::parse($dailyLog->end_time)->diffInSeconds(Carbon::parse($dailyLog->start_time));
                }
                return $dailyLog;
            });
    }
}
