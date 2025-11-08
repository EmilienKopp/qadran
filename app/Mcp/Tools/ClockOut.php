<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ClockEntryResource;
use App\Models\ClockEntry;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class ClockOut extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Clock out to stop tracking time. This updates an existing clock entry with the current timestamp.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $entryId = $request->input('clock_entry_id');

        if ($entryId) {
            // Clock out from a specific entry
            $entry = ClockEntry::find($entryId);

            if (! $entry) {
                return Response::content([
                    [
                        'type' => 'text',
                        'text' => "Clock entry with ID {$entryId} not found.",
                    ],
                ]);
            }

            if ($entry->out) {
                return Response::content([
                    [
                        'type' => 'text',
                        'text' => "Clock entry {$entryId} is already clocked out.",
                    ],
                ]);
            }
        } else {
            // Find the active clock entry for the user
            $entry = ClockEntry::where('user_id', $request->input('user_id'))
                ->whereNull('out')
                ->first();

            if (! $entry) {
                return Response::content([
                    [
                        'type' => 'text',
                        'text' => 'No active clock entry found. Please clock in first.',
                    ],
                ]);
            }
        }

        $entry->update([
            'out' => now(),
            'notes' => $request->input('notes', $entry->notes),
        ]);

        $entry->load('project');
        $resource = new ClockEntryResource($entry);

        return Response::content([
            [
                'type' => 'text',
                'text' => "Clocked out successfully at {$entry->out}. Duration: {$entry->duration_seconds} seconds",
            ],
            [
                'type' => 'resource',
                'resource' => [
                    'uri' => "clock-entry://{$entry->id}",
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
            'user_id' => $schema->integer()->description('The ID of the user clocking out (required if clock_entry_id not provided)'),
            'clock_entry_id' => $schema->integer()->description('The ID of the specific clock entry to clock out (optional)'),
            'notes' => $schema->string()->description('Additional notes for the clock entry'),
        ];
    }
}
