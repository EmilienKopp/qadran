<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    public function index()
    {
        return response()->json($this->taskRepository->all());
    }

    public function show(int $id)
    {
        $task = $this->taskRepository->find($id);

        return response()->json($task);
    }

    public function byProject(int $projectId)
    {
        $tasks = $this->taskRepository->findByProject($projectId);

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
        ]);

        $task = $this->taskRepository->create($validated);

        return response()->json($task, 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|exists:projects,id',
            'name' => 'sometimes|string|max:255',
        ]);

        $task = $this->taskRepository->update($id, $validated);
        if (! $task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    public function destroy(int $id)
    {
        $deleted = $this->taskRepository->delete($id);
        if (! $deleted) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json(['message' => 'Task deleted'], 200);
    }
}
