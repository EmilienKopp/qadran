<?php

namespace App\Mcp\Tools;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;

class CreateProject extends TenantAwareTool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Create a new project in the Qadran system. A project is a container for tasks and time tracking.
        This tool operates within the authenticated user's tenant context.
    MARKDOWN;

    /**
     * Handle the tool request with tenant and user context.
     */
    protected function handleTenantRequest(Request $request): Response
    {
        // Check if user has permission to create projects
        if (!$this->userCan('create-projects') && !$this->userHasRole(['admin', 'project-manager'])) {
            return $this->errorResponse('You do not have permission to create projects.');
        }

        try {
            $project = Project::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'type' => $request->input('type', ProjectType::Other->value),
                'status' => $request->input('status', ProjectStatus::Active->value),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'location' => $request->input('location'),
                'icon' => $request->input('icon'),
                // Automatically associate with user's organization if they have one
                'organization_id' => $this->getUserOrganizations()->first()?->id,
            ]);

            $resource = $this->formatModelResource('Project', $project, 'project');

            return $this->successResponse(
                "Project '{$project->name}' created successfully with ID {$project->id} in tenant '{$this->tenant->host}'."
            );

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create project: ' . $e->getMessage());
        }
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
            'description' => $schema->string()->description('A description of the project'),
            'type' => $schema->string()->enum(ProjectType::values())->description('The type of project'),
            'status' => $schema->string()->enum(ProjectStatus::values())->description('The status of the project'),
            'start_date' => $schema->string()->description('The start date of the project (ISO 8601 format)'),
            'end_date' => $schema->string()->description('The end date of the project (ISO 8601 format)'),
            'location' => $schema->string()->description('The location of the project'),
            'icon' => $schema->string()->description('An icon for the project'),
        ];
    }
}
