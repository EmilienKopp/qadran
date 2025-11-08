<?php

namespace App\Mcp\Tools;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class ListTasks extends Tool
{
  /**
   * The tool's description.
   */
  protected string $description = <<<'MARKDOWN'
      Authenticate user to a tenant organization and set the tenant context for subsequent operations.
    MARKDOWN;

  /**
   * Handle the tool request.
   */
  public function handle(Request $request): array
  {
    
    return [

    ];
  }

  /**
   * Get the tool's input schema.
   *
   * @return array<string, \Illuminate\JsonSchema\JsonSchema>
   */
  public function schema(JsonSchema $schema): array
  {
    return [
      
    ];
  }
}
