<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatchStoreActivityRequest;
use App\Models\Activity;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\ActivityLog;
use App\Models\Views\DailyLog;
use App\Repositories\ProjectRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use App\Models\TaskCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ActivityController extends Controller
{
    public function __construct(
        public UserRepositoryInterface $userRepository,
        public ProjectRepositoryInterface $projectRepository
    )
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $taskCategories = TaskCategory::all();
        $projects = auth()->user()->involvedProjects;

        $date = $request->query('date') ?? Carbon::today()->format('Y-m-d');

        $dailyLogs = DailyLog::getMonthly($date);

        return inertia('Activity/Index', compact( 'projects', 'dailyLogs', 'taskCategories', 'date'));
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
        Activity::where('user_id', auth()->user()->id)
            ->where('project_id', $activities[0]['project_id'])
            ->where('date', Carbon::today())
            ->delete();
        Activity::upsert($activities, ['project_id', 'user_id', 'task_category_id', 'date']);
        return to_route('activities.show', ['date' => $validated['date']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $date = null)
    {
        $date ??= $request->query('date') ?? Carbon::today()->format('Y-m-d');

        $user = User::find(auth('tenant')->user()->id);
        // $projects = $user->getInvolvedProjects();
        $projects = $this->projectRepository->findForUser($user);

        $taskCategories = TaskCategory::all()->transform(function ($taskCategory) {
            $taskCategory->name = __($taskCategory->name);
            return $taskCategory;
        });
        
        $activities = ActivityLog::with(['clockEntries', 'activityType', 'task'])
            ->get();
        
        $dailyLogs = DailyLog::getDaily($date);
        

        return inertia('Activity/Daily/Show', compact('activities', 'projects', 'dailyLogs', 'taskCategories', 'date'));
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
