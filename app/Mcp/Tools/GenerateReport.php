<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ReportResource;
use App\Models\ClockEntry;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class GenerateReport extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Generate a time tracking report for a specific date or date range. Returns clock entries with summaries.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $userId = $request->input('user_id');

        $query = ClockEntry::query()
            ->whereDate('in', $date)
            ->with('project');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $entries = $query->get();

        $totalSeconds = $entries->sum('duration_seconds');
        $totalHours = round($totalSeconds / 3600, 2);

        $projectBreakdown = $entries->groupBy('project_id')->map(function ($projectEntries) {
            $project = $projectEntries->first()->project;

            return [
                'project_id' => $project?->id,
                'project_name' => $project?->name ?? 'No Project',
                'total_seconds' => $projectEntries->sum('duration_seconds'),
                'total_hours' => round($projectEntries->sum('duration_seconds') / 3600, 2),
                'entry_count' => $projectEntries->count(),
            ];
        })->values();

        $reportData = [
            'date' => $date,
            'entries' => $entries,
            'total_hours' => $totalHours,
            'total_seconds' => $totalSeconds,
            'projects' => $projectBreakdown,
        ];

        $resource = new ReportResource($reportData);

        return Response::content([
            [
                'type' => 'text',
                'text' => "Report for {$date}: Total {$totalHours} hours ({$totalSeconds} seconds) across {$entries->count()} entries.",
            ],
            [
                'type' => 'resource',
                'resource' => [
                    'uri' => "report://{$date}",
                    'mimeType' => 'application/json',
                    'text' => json_encode($resource->toArray(request())),
                ],
            ],
        ]);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'date' => $schema->string()->description('The date for the report (YYYY-MM-DD format)')->optional(),
            'user_id' => $schema->integer()->description('Filter report by user ID')->optional(),
        ];
    }
}
