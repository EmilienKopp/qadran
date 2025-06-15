<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\AIService;

class ReportController extends Controller
{
    private $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }
    public function generate(Request $request)
    {
        try {
            $content = $this->aiService->generateGitSummary(log: $request->input('original_log'), reportType: $request->input('report_type'));
            \Log::info('Git report generated successfully', [
                'user_id' => auth()->id(),
                'content' => $content,
            ]);
            return Inertia::render('Report/Create', [
                'content' => $content,
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Report/Create', [
                'errors' => [
                    'content' => 'Failed to generate report: ' . $e->getMessage(),
                ]
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = auth()->user()->reports()
            ->with('user')
            ->latest()
            ->paginate(10);
        return Inertia::render('Report/Index', [
            'reports' => $reports,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Report/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
            'report_type' => 'required|in:technical,financial,operational,task_based',
            'title' => 'required|string|min:5|max:255',
        ]);

        $report = Report::create([
            'user_id' => auth()->id(),
            'content' => $validatedData['content'],
            'report_type' => $validatedData['report_type'],
            'title' => $validatedData['title'],
        ]);

        return Inertia::render('Report/Show', [
            'report' => $report,
            'message' => 'Report created successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
