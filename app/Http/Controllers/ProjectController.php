<?php

namespace App\Http\Controllers;

use App\Domain\Project\ProjectActivitySummary;
use App\Domain\Project\ProjectCost;
use App\Enums\ProjectStatus;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends HybridController
{
    /**
     * Display a listing of the resource.
     */
    public function index(?string $account = null)
    {
        $projects = ProjectRepository::getForUser();

        return $this->respond($projects, function ($projects) {
            return Inertia::render('Project/Index', [
                'projects' => Inertia::always($projects),
            ]);
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(?string $account = null)
    {
        return inertia('Project/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request, ?string $account = null)
    {
        $validated = $request->validated();
        Auth::user()->projects()->create($validated);

        return redirect()->route('project.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(?string $account, Project $project)
    {
        return inertia('Project/Show', [
            'project' => Inertia::always($project),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(?string $account, Project $project)
    {
        $costManager = new ProjectCost($project);
        $taskManager = new ProjectActivitySummary($project);
        $cost = $costManager->calculateCost();
        $summary = $taskManager->getActivityLogs();

        return inertia('Project/Edit', [
            'project' => Inertia::always($project),
            'statusOptions' => Inertia::always(ProjectStatus::toSelectOptions()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, ?string $account, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(?string $account, Project $project)
    {
        $project->delete();

        return to_route('project.index');
    }
}
