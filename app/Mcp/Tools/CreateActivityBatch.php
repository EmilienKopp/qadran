<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use App\Models\ClockEntry;
use Illuminate\JsonSchema\JsonSchema;
use Illuminate\Support\Collection;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class CreateActivityBatch extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Create multiple activity log entries for the same clock entry in a single operation. This is useful for logging several activities that occurred during the same day/clock entry.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        // Verify the clock entry exists
        $clockEntry = ClockEntry::find($request->input('clock_entry_id'));
        
        if (!$clockEntry) {
            return Response::content([
                [
                    'type' => 'text',
                    'text' => "Clock entry with ID {$request->input('clock_entry_id')} not found.",
                ],
            ]);
        }

        $activities = $request->input('activities', []);
        
        if (empty($activities)) {
            return Response::content([
                [
                    'type' => 'text',
                    'text' => "No activities provided. Please provide at least one activity in the 'activities' array.",
                ],
            ]);
        }

        $createdActivities = new Collection();
        
        foreach ($activities as $activityData) {
            $activityLog = ActivityLog::create([
                'clock_entry_id' => $request->input('clock_entry_id'),
                'activity_type_id' => $activityData['activity_type_id'] ?? null,
                'task_id' => $activityData['task_id'] ?? null,
                'start_offset_seconds' => $activityData['start_offset_seconds'] ?? null,
                'end_offset_seconds' => $activityData['end_offset_seconds'] ?? null,
                'notes' => $activityData['notes'] ?? null,
            ]);
            
            $activityLog->load(['clockEntry', 'activityType', 'task']);
            $createdActivities->push($activityLog);
        }

        $resources = ActivityLogResource::collection($createdActivities);
        $count = $createdActivities->count();

        return Response::content([
            [
                'type' => 'text',
                'text' => "Successfully created {$count} activity log(s) for clock entry {$clockEntry->id}.",
            ],
            [
                'type' => 'resource',
                'resource' => [
                    'uri' => "activity-logs://batch/{$clockEntry->id}",
                    'mimeType' => 'application/json',
                    'text' => json_encode($resources->toArray(request())),
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
            'clock_entry_id' => $schema->integer()->description('The ID of the clock entry these activities belong to'),
            'activities' => $schema->array()->description('Array of activity objects to create')->items(
                $schema->object()
                    ->properties([
                        'activity_type_id' => $schema->integer()->description('The ID of the activity type')->optional(),
                        'task_id' => $schema->integer()->description('The ID of the task associated with this activity')->optional(),
                        'start_offset_seconds' => $schema->integer()->description('Start time offset in seconds from clock entry start')->optional(),
                        'end_offset_seconds' => $schema->integer()->description('End time offset in seconds from clock entry start')->optional(),
                        'notes' => $schema->string()->description('Notes about this activity')->optional(),
                    ])
            ),
        ];
    }
}
