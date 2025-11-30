<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepositoryInterface;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected ReportRepositoryInterface $reportRepository
    ) {}

    public function index()
    {
        return response()->json($this->reportRepository->all());
    }

    public function show(int $id)
    {
        $report = $this->reportRepository->find($id);

        return response()->json($report);
    }

    public function byProject(int $projectId)
    {
        $reports = $this->reportRepository->findByProject($projectId);

        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $report = $this->reportRepository->create($validated);

        return response()->json($report, 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|exists:projects,id',
        ]);

        $report = $this->reportRepository->update($id, $validated);
        if (! $report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        return response()->json($report);
    }

    public function destroy(int $id)
    {
        $deleted = $this->reportRepository->delete($id);
        if (! $deleted) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        return response()->json(['message' => 'Report deleted'], 200);
    }
}
