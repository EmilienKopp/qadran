<?php

namespace App\Mcp\Tools;

use App\Models\Interference;
use App\Repositories\InterferenceRepository;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;

class RegisterInterference extends TenantAwareTool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Register an interference - a brief interruption of work during a current clock entry.
        This allows tracking time spent on a different project or task while clocked in on another.
        
        An interference has:
        - in/out timestamps for when the interference occurred
        - optional clock_entry_id to track which clock entry was interrupted
        - optional project_id to track what project the interference was for
        - optional notes to describe the interference
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    protected function handleTenantRequest(Request $request): Response
    {
        $interference = InterferenceRepository::register([
            'user_id' => $this->user->id,
            'in' => $request->get('in') ?? now(),
            'out' => $request->get('out') ?? now(),
            'project_id' => $request->get('project_id'),
            'clock_entry_id' => $request->get('clock_entry_id'),
            'timezone' => $request->get('timezone', $this->user->timezone ?? config('app.timezone')),
            'notes' => $request->get('notes'),
        ]);

        $interference->load('project', 'clockEntry');

        $projectName = $interference->project?->name ?? 'No project';
        $duration = $interference->duration_seconds 
            ? round($interference->duration_seconds / 60, 1) . ' minutes'
            : 'unknown duration';

        return Response::text(
            "Interference registered successfully. " .
            "Project: {$projectName}, Duration: {$duration}, " .
            "From {$interference->in} to {$interference->out}. " .
            "Interference ID: {$interference->id}"
        );
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'in' => $schema->string()->description('The start time of the interference (ISO 8601 format). Defaults to now if not provided.'),
            'out' => $schema->string()->description('The end time of the interference (ISO 8601 format). Defaults to now if not provided.'),
            'project_id' => $schema->integer()->description('The ID of the project the interference was for (optional)'),
            'clock_entry_id' => $schema->integer()->description('The ID of the clock entry that was interrupted (optional)'),
            'timezone' => $schema->string()->description('The timezone for the interference times'),
            'notes' => $schema->string()->description('Notes describing the interference'),
        ];
    }
}
