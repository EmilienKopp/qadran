<?php

namespace App\Mcp\Tools;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class ListTasks extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        List all tasks, optionally filtered by project or completion status.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $query = Task::query()->with('project');

        if ($request->has('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }

        if ($request->has('completed')) {
            $query->where('completed', $request->input('completed'));
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        $limit = min($request->input('limit', 50), 100);
        $tasks = $query->limit($limit)->get();

        $resources = TaskResource::collection($tasks);

        $text = "Found {$tasks->count()} task(s)";
        if ($request->has('project_id')) {
            $text .= " for project ID {$request->input('project_id')}";
        }
        if ($request->has('completed')) {
            $completedStatus = $request->input('completed') ? 'completed' : 'incomplete';
            $text .= " ({$completedStatus})";
        }

        return Response::content([
            [
                'type' => 'text',
                'text' => $text,
            ],
            [
                'type' => 'resource',
                'resource' => [
                    'uri' => 'tasks://list',
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
            'project_id' => $schema->integer()->description('Filter tasks by project ID')->optional(),
            'completed' => $schema->boolean()->description('Filter by completion status')->optional(),
            'priority' => $schema->integer()->description('Filter by priority level')->optional(),
            'limit' => $schema->integer()->description('Maximum number of tasks to return (default 50, max 100)')->optional(),
        ];
    }
}
