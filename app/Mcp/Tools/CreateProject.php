<?php

namespace App\Mcp\Tools;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class CreateProject extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Create a new project in the Qadran system. A project is a container for tasks and time tracking.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $project = Project::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'type' => $request->input('type', ProjectType::Other->value),
            'status' => $request->input('status', ProjectStatus::Active->value),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'location' => $request->input('location'),
            'icon' => $request->input('icon'),
        ]);

        $resource = new ProjectResource($project);

        return Response::content([
            [
                'type' => 'text',
                'text' => "Project '{$project->name}' created successfully with ID {$project->id}.",
            ],
            [
                'type' => 'resource',
                'resource' => [
                    'uri' => "project://{$project->id}",
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
            'name' => $schema->string()->description('The name of the project'),
            'description' => $schema->string()->description('A description of the project')->optional(),
            'type' => $schema->enum(...ProjectType::values())->description('The type of project')->optional(),
            'status' => $schema->enum(...ProjectStatus::values())->description('The status of the project')->optional(),
            'start_date' => $schema->string()->description('The start date of the project (ISO 8601 format)')->optional(),
            'end_date' => $schema->string()->description('The end date of the project (ISO 8601 format)')->optional(),
            'location' => $schema->string()->description('The location of the project')->optional(),
            'icon' => $schema->string()->description('An icon for the project')->optional(),
        ];
    }
}
