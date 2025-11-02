<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ClockEntryResource;
use App\Models\ClockEntry;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class ClockIn extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Clock in to start tracking time for a project. This creates a new clock entry with the current timestamp.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        // Check if user already has an active clock entry (no 'out' time)
        $activeEntry = ClockEntry::where('user_id', $request->input('user_id'))
            ->whereNull('out')
            ->first();

        if ($activeEntry) {
            return Response::content([
                [
                    'type' => 'text',
                    'text' => "You already have an active clock entry (ID {$activeEntry->id}). Please clock out first.",
                ],
            ]);
        }

        $entry = ClockEntry::create([
            'user_id' => $request->input('user_id'),
            'project_id' => $request->input('project_id'),
            'in' => now(),
            'timezone' => $request->input('timezone', config('app.timezone')),
            'notes' => $request->input('notes'),
        ]);

        $entry->load('project');
        $resource = new ClockEntryResource($entry);

        return Response::content([
            [
                'type' => 'text',
                'text' => "Clocked in successfully at {$entry->in}. Entry ID: {$entry->id}",
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
            'user_id' => $schema->integer()->description('The ID of the user clocking in'),
            'project_id' => $schema->integer()->description('The ID of the project to clock in to')->optional(),
            'timezone' => $schema->string()->description('The timezone for the clock entry')->optional(),
            'notes' => $schema->string()->description('Notes for the clock entry')->optional(),
        ];
    }
}
