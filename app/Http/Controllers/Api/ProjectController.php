<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProjectRepositoryInterface;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    public function index()
    {
        return response()->json($this->projectRepository->all());
    }

    public function show(int $id)
    {
        $project = $this->projectRepository->find($id);
        return response()->json($project);
    }

    public function byOrganization(int $organizationId)
    {
        $projects = $this->projectRepository->findByOrganization($organizationId);
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        $project = $this->projectRepository->create($validated);
        return response()->json($project, 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'organization_id' => 'sometimes|exists:organizations,id',
        ]);

        $project = $this->projectRepository->update($id, $validated);
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }
        return response()->json($project);
    }

    public function destroy(int $id)
    {
        $deleted = $this->projectRepository->delete($id);
        if (!$deleted) {
            return response()->json(['error' => 'Project not found'], 404);
        }
        return response()->json(['message' => 'Project deleted'], 200);
    }
}
