<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\ActivityType;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use App\Models\Views\DailyLog;
use App\Repositories\ProjectRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct(
        public UserRepositoryInterface $userRepository,
        public ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $taskCategories = TaskCategory::all();
        $projects = auth()->user()->involvedProjects;

        $date = $request->query('date') ?? Carbon::today()->format('Y-m-d');

        $dailyLogs = DailyLog::getMonthly($date);

        return inertia('Activity/Index', compact('projects', 'dailyLogs', 'taskCategories', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        $validated = $request->validated();
        $activities = $validated['activities'];

        // Filter out activities with no duration
        $activities = array_filter($activities, function ($activity) {
            return isset($activity['duration']) && $activity['duration'] > 0;
        });

        if (empty($activities)) {
            return back()->with('error', 'No activities with duration to save.');
        }

        // Get the first activity to determine project and date
        $firstActivity = reset($activities);

        // Delete existing activities for this user, project, and date
        Activity::where('user_id', auth()->user()->id)
            ->where('project_id', $firstActivity['project_id'])
            ->where('date', $validated['date'])
            ->delete();

        // Insert new activities
        foreach ($activities as $activity) {
            Activity::create([
                'user_id' => auth()->user()->id,
                'project_id' => $activity['project_id'],
                'task_category_id' => $activity['task_category_id'],
                'date' => $validated['date'],
                'duration' => $activity['duration'],
                'notes' => $activity['notes'] ?? null,
            ]);
        }

        return back()->with('success', 'Activities saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $date = null)
    {
        $date ??= $request->query('date') ?? Carbon::today()->format('Y-m-d');

        $user = User::find(auth('tenant')->user()->id);
        $projects = $this->projectRepository->findForUser($user);

        $taskCategories = TaskCategory::all()->transform(function ($taskCategory) {
            $taskCategory->name = __($taskCategory->name);

            return $taskCategory;
        });

        $activities = ActivityLog::with(['clockEntry', 'activityType', 'task'])
            ->get();

        $activityTypes = ActivityType::all();

        $tasks = Task::whereIn('project_id', $projects->pluck('id'))->get();

        $dailyLogs = DailyLog::getDaily($date);

        return inertia('Activity/Daily/Show', compact('activities', 'projects', 'dailyLogs', 'taskCategories', 'activityTypes', 'tasks', 'date'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
