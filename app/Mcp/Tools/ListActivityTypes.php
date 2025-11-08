<?php

namespace App\Mcp\Tools;

use App\Models\ActivityType;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class ListActivityTypes extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        List all available activity types in the system. Activity types categorize different kinds of work activities.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $query = ActivityType::query();

        // Optional name filter
        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->get('name').'%');
        }

        $limit = min($request->get('limit', 50), 100);
        $activityTypes = $query->limit($limit)->get();

        $count = $activityTypes->count();

        return Response::text("Found {$count} activity type(s).");
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Filter activity types by name (partial match)'),
            'limit' => $schema->integer()->description('Maximum number of activity types to return (default 50, max 100)'),
        ];
    }
}
