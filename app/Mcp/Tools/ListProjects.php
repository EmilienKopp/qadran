<?php

namespace App\Mcp\Tools;

use App\Models\Project;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class ListProjects extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        List all projects, optionally filtered by status or type.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): array
    {
        $query = Project::query();

        // Get validated parameters from the request
        $validated = $request->validate([
            'status' => 'nullable|string',
            'type' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (! empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        $limit = $validated['limit'] ?? 50;
        $projects = $query->limit($limit)->get();

        $summary = "Found {$projects->count()} project(s)";
        if (! empty($validated['status'])) {
            $summary .= " with status '{$validated['status']}'";
        }
        if (! empty($validated['type'])) {
            $summary .= " of type '{$validated['type']}'";
        }

        $table = "\n\n## Projects\n\n";
        $table .= "| ID | Name | Type | Status | Description |\n";
        $table .= "|---|---|---|---|---|\n";

        foreach ($projects as $project) {
            $name = $project->name ?? 'N/A';
            $type = $project->type ?? 'N/A';
            $status = $project->status ?? 'N/A';
            $description = $project->description ? substr($project->description, 0, 50).'...' : '-';

            $table .= "| {$project->id} | {$name} | {$type} | {$status} | {$description} |\n";
        }

        // Return array of Response objects
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
            'status' => $schema->string()->description('Filter projects by status (active, inactive, archived, deleted, pending)'),
            'type' => $schema->string()->description('Filter projects by type (open_source, commercial, internal, etc.)'),
            'limit' => $schema->integer()->description('Maximum number of projects to return (default 50, max 100)'),
        ];
    }
}
