<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ActivityTypeResource;
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
            $query->where('name', 'like', '%'.$request->input('name').'%');
        }

        $limit = min($request->input('limit', 50), 100);
        $activityTypes = $query->limit($limit)->get();

        $resources = ActivityTypeResource::collection($activityTypes);
        $count = $activityTypes->count();

        return Response::content([
            [
                'type' => 'text',
                'text' => "Found {$count} activity type(s).",
            ],
            [
                'type' => 'resource',
                'resource' => [
                    'uri' => 'activity-types://list',
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
            'name' => $schema->string()->description('Filter activity types by name (partial match)'),
            'limit' => $schema->integer()->description('Maximum number of activity types to return (default 50, max 100)'),
        ];
    }
}
