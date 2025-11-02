<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ProjectResource;
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
    public function handle(Request $request): Response
    {
        $query = Project::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        $limit = min($request->input('limit', 50), 100);
        $projects = $query->limit($limit)->get();

        $resources = ProjectResource::collection($projects);

        $text = "Found {$projects->count()} project(s)";
        if ($request->has('status')) {
            $text .= " with status '{$request->input('status')}'";
        }
        if ($request->has('type')) {
            $text .= " of type '{$request->input('type')}'";
        }

        return Response::content([
            [
                'type' => 'text',
                'text' => $text,
            ],
            [
                'type' => 'resource',
                'resource' => [
                    'uri' => 'projects://list',
                    'mimeType' => 'application/json',
                    'text' => json_encode($resources->resolve()),
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
            'status' => $schema->string()->description('Filter projects by status (active, inactive, archived, deleted, pending)')->optional(),
            'type' => $schema->string()->description('Filter projects by type (open_source, commercial, internal, etc.)')->optional(),
            'limit' => $schema->integer()->description('Maximum number of projects to return (default 50, max 100)')->optional(),
        ];
    }
}
