<?php

namespace App\Mcp\Tools;

use App\Models\ClockEntry;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class GetClockEntries extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Retrieve clock entries with optional filtering by date, user, and project. Returns a list of clock entries with their details.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): array
    {
        $query = ClockEntry::query()->with(['project', 'user']);

        // Get validated parameters from the request
        $validated = $request->validate([
            'date' => 'nullable|date',
            'user_id' => 'nullable|integer',
            'project_id' => 'nullable|integer',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        // Filter by specific date
        if (! empty($validated['date'])) {
            $query->whereDate('in', $validated['date']);
        }

        // Filter by user
        if (! empty($validated['user_id'])) {
            $query->where('user_id', $validated['user_id']);
        } else {
            $query->where('user_id', request()->user()->id);
        }

        // Filter by project
        if (! empty($validated['project_id'])) {
            $query->where('project_id', $validated['project_id']);
        }

        $limit = $validated['limit'] ?? 50;
        $entries = $query->orderBy('in', 'desc')->limit($limit)->get();

        // Calculate totals
        $totalSeconds = $entries->sum('duration_seconds');
        $totalHours = round($totalSeconds / 3600, 2);

        // Build summary text
        $summary = "Found {$entries->count()} clock entry(ies)";
        if (! empty($validated['date'])) {
            $summary .= " for date {$validated['date']}";
        }
        if (! empty($validated['user_id'])) {
            $summary .= " for user ID {$validated['user_id']}";
        }
        if (! empty($validated['project_id'])) {
            $summary .= " for project ID {$validated['project_id']}";
        }
        if ($entries->count() > 0) {
            $summary .= ". Total time: {$totalHours} hours ({$totalSeconds} seconds)";
        }


        $table = "\n\n## Clock Entries\n\n";
        $table .= "| ID | Project | ProjectId | Clock In | Clock Out | Duration | Notes |\n";
        $table .= "|---|---|---|---|---|---|\n";

        foreach ($entries as $entry) {
            $projectName = $entry->project?->name ?? 'N/A';
            $projectId = $entry->project?->id ?? 'N/A';
            $clockIn = $entry->in ?? 'N/A';
            $clockOut = $entry->out ?? 'Active';
            $duration = $entry->duration_seconds ? round($entry->duration_seconds / 3600, 2).' hrs' : 'Active';
            $notes = $entry->notes ? substr($entry->notes, 0, 30).'...' : '-';

            $table .= "| {$entry->id} | {$projectName} | {$projectId} | {$clockIn} | {$clockOut} | {$duration} | {$notes} |\n";
        }

        return [
            Response::text($summary.$table),
        ];
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'date' => $schema->string()
                ->description('Filter clock entries by date (YYYY-MM-DD format)'),
            'user_id' => $schema->integer()
                ->description('Filter clock entries by user ID'),
            'project_id' => $schema->integer()
                ->description('Filter clock entries by project ID'),
            'limit' => $schema->integer()
                ->description('Maximum number of entries to return (default 50, max 100)'),
        ];
    }
}
