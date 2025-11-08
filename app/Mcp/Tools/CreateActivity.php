<?php

namespace App\Mcp\Tools;

use App\Models\ActivityLog;
use App\Models\ClockEntry;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class CreateActivity extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Create a new activity log entry associated with a clock entry. Activity logs track what you did during a clock entry with optional time offsets.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        // Verify the clock entry exists
        $clockEntry = ClockEntry::find($request->get('clock_entry_id'));

        if (! $clockEntry) {
            return Response::text("Clock entry with ID {$request->get('clock_entry_id')} not found.");
        }

        $activityLog = ActivityLog::create([
            'clock_entry_id' => $request->get('clock_entry_id'),
            'activity_type_id' => $request->get('activity_type_id'),
            'task_id' => $request->get('task_id'),
            'start_offset_seconds' => $request->get('start_offset_seconds'),
            'end_offset_seconds' => $request->get('end_offset_seconds'),
            'notes' => $request->get('notes'),
        ]);

        $activityLog->load(['clockEntry', 'activityType', 'task']);

        return Response::text("Activity log created successfully with ID {$activityLog->id}.");
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'clock_entry_id' => $schema->integer()->description('The ID of the clock entry this activity belongs to'),
            'activity_type_id' => $schema->integer()->description('The ID of the activity type'),
            'task_id' => $schema->integer()->description('The ID of the task associated with this activity'),
            'start_offset_seconds' => $schema->integer()->description('Start time offset in seconds from clock entry start'),
            'end_offset_seconds' => $schema->integer()->description('End time offset in seconds from clock entry start'),
            'notes' => $schema->string()->description('Notes about this activity'),
        ];
    }
}
