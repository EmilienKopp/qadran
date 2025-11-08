<?php

namespace App\Mcp\Tools;

use App\Enums\TaskPriority;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class CreateTask extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Create a new task in the Qadran system. Tasks can be associated with projects or standalone.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $task = Task::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'project_id' => $request->input('project_id'),
            'priority' => $request->input('priority', TaskPriority::None->value),
            'completed' => $request->input('completed', false),
        ]);

        $task->load('project');
        $resource = new TaskResource($task);

        return Response::content([
            [
                'type' => 'text',
                'text' => "Task '{$task->name}' created successfully with ID {$task->id}.",
            ],
            [
                'type' => 'resource',
                'resource' => [
                    'uri' => "task://{$task->id}",
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
            'name' => $schema->string()->description('The name of the task'),
            'description' => $schema->string()->description('A description of the task'),
            'project_id' => $schema->integer()->description('The ID of the project this task belongs to'),
            'priority' => $schema->integer()->description('The priority of the task (0-6, -1 for blocker)'),
            'completed' => $schema->boolean()->description('Whether the task is completed'),
        ];
    }
}
