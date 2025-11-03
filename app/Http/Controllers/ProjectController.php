<?php

namespace App\Http\Controllers;

use App\Domain\Project\ProjectCost;
use App\Domain\Project\ProjectActivitySummary;
use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends HybridController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = ProjectRepository::getForUser();

        return $this->respond($projects, function ($projects) {
            return Inertia::render('Project/Index', [
                'projects' => Inertia::always($projects)
            ]);
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Project/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        Auth::user()->projects()->create($validated);
        return redirect()->route('project.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return inertia('Project/Show', [
            'project' => Inertia::always($project)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
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
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return to_route('project.index');
    }
}
