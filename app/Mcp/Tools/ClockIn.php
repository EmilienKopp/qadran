<?php

namespace App\Mcp\Tools;

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
        $userId = $request->get('user_id') ?? request()->user()->id;

        // Check if user already has an active clock entry (no 'out' time)
        $activeEntry = ClockEntry::where('user_id', $userId)
            ->whereNull('out')
            ->first();

        if ($activeEntry) {
            return Response::text("You are already clocked in since {$activeEntry->in}. Please clock out before clocking in again.");
        }

        $timezone = $request->get('timezone', request()->user()->timezone ?? config('app.timezone'));

        $entry = ClockEntry::create([
            'user_id' => $userId,
            'project_id' => $request->get('project_id'),
            'in' => now(),
            'timezone' => $timezone,
            'notes' => $request->get('notes'),
        ]);

        $entry->load('project');

        return Response::text("Clocked in successfully at {$entry->in}. Entry ID: {$entry->id}");
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
            'project_id' => $schema->integer()->description('The ID of the project to clock in to'),
            'timezone' => $schema->string()->description('The timezone for the clock entry'),
            'notes' => $schema->string()->description('Notes for the clock entry'),
        ];
    }
}
