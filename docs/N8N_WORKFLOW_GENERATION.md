# N8N Agent Workflow Generation

## Overview

The `N8NService::generateAgentWorkflow()` method generates a complete n8n workflow template for an AI Agent with MCP (Model Context Protocol) integration. This workflow includes:

- **Webhook Endpoint** - Receives requests from your Laravel application
- **Server Authentication** - Validates tenant tokens via your Laravel API
- **AI Agent** - Claude AI model configured with MCP tools
- **MCP Client** - Connects to your MCP server for tool execution
- **Response Handler** - Returns results back to the caller

## Method Signature

```php
public function generateAgentWorkflow(
    array $mcpCredentials,      // MCP Bearer token credentials
    string $mcpEndpointUrl,     // Full URL to MCP endpoint
    ?array $aiCredentials = null, // Optional AI credentials (defaults provided)
    ?string $appUrl = null      // Optional APP_URL (defaults to config('app.url'))
): array
```

## Parameters

### Required Parameters

#### `$mcpCredentials` (array)
MCP Bearer authentication credentials created in n8n.

**Structure:**
```php
[
    'id' => 'credential-id-from-n8n',
    'name' => 'Credential Name'
]
```

#### `$mcpEndpointUrl` (string)
The full URL to your MCP server endpoint.

**Examples:**
```php
'http://host.docker.internal:8000/mcp/qadran'
'https://app.example.com/mcp/tenant-server'
```

### Optional Parameters

#### `$aiCredentials` (array|null)
Anthropic AI credentials for the Claude model. If not provided, defaults to:

```php
[
    'id' => 'V3lx69vlrI5hqH8S',
    'name' => 'Anthropic account'
]
```

#### `$appUrl` (string|null)
Base URL for the decrypt-tenant endpoint. If not provided, uses `config('app.url')`.

**Examples:**
```php
'http://localhost:8000'
'https://app.example.com'
```

## Usage Examples

### Basic Usage (Using Defaults)

```php
use App\Services\N8NService;

$n8nService = app(N8NService::class);

// Generate workflow with minimal configuration
$workflow = $n8nService->generateAgentWorkflow(
    mcpCredentials: [
        'id' => 'OTH6Ir7n6n5TXHSN',
        'name' => 'Qadran MCP Bearer'
    ],
    mcpEndpointUrl: 'http://host.docker.internal:8000/mcp/qadran'
);

// Create the workflow in n8n
$createdWorkflow = $n8nService->createWorkflow($workflow);
```

### Advanced Usage (Custom Credentials & URL)

```php
use App\Services\N8NService;

$n8nService = app(N8NService::class);

// Generate workflow with all custom parameters
$workflow = $n8nService->generateAgentWorkflow(
    mcpCredentials: [
        'id' => 'custom-mcp-bearer-id',
        'name' => 'Production MCP Token'
    ],
    mcpEndpointUrl: 'https://api.production.com/mcp/main',
    aiCredentials: [
        'id' => 'custom-anthropic-id',
        'name' => 'Production Anthropic Account'
    ],
    appUrl: 'https://production.example.com'
);

// Create the workflow in n8n
$createdWorkflow = $n8nService->createWorkflow($workflow);
```

### Dynamic Tenant-Specific Workflow

```php
use App\Services\N8NService;
use App\Models\Landlord\Tenant;

$n8nService = app(N8NService::class);
$tenant = Tenant::find($tenantId);

// Generate a tenant-specific workflow
$workflow = $n8nService->generateAgentWorkflow(
    mcpCredentials: [
        'id' => $tenant->n8n_mcp_credential_id,
        'name' => "MCP Bearer - {$tenant->name}"
    ],
    mcpEndpointUrl: "https://app.example.com/mcp/{$tenant->slug}",
    appUrl: config('app.url')
);

$createdWorkflow = $n8nService->createWorkflow($workflow);

// Store the workflow ID for later use
$tenant->update(['n8n_workflow_id' => $createdWorkflow['id']]);
```

### Artisan Command Example

```php
// app/Console/Commands/CreateTenantN8nWorkflow.php

namespace App\Console\Commands;

use App\Models\Landlord\Tenant;
use App\Services\N8NService;
use Illuminate\Console\Command;

class CreateTenantN8nWorkflow extends Command
{
    protected $signature = 'tenant:create-workflow {tenant_id}';
    protected $description = 'Create an n8n workflow for a tenant';

    public function handle(N8NService $n8nService): int
    {
        $tenant = Tenant::findOrFail($this->argument('tenant_id'));

        $this->info("Creating n8n workflow for tenant: {$tenant->name}");

        $workflow = $n8nService->generateAgentWorkflow(
            mcpCredentials: [
                'id' => config('services.n8n.default_mcp_credential_id'),
                'name' => "MCP - {$tenant->name}"
            ],
            mcpEndpointUrl: route('mcp.tenant', ['tenant' => $tenant->slug]),
            appUrl: config('app.url')
        );

        $created = $n8nService->createWorkflow($workflow);

        $tenant->update(['n8n_workflow_id' => $created['id']]);

        $this->info("✓ Workflow created with ID: {$created['id']}");
        $this->info("  Webhook URL: {$created['webhookUrl'] ?? 'N/A'}");

        return self::SUCCESS;
    }
}
```

## Generated Workflow Structure

The generated workflow includes these nodes:

1. **Webhook** - POST endpoint with randomized UUID path
2. **Server Auth** - HTTP request to `/api/n8n/decrypt-tenant` for tenant validation
3. **AI Agent** - Claude AI configured with system prompt from webhook body
4. **Anthropic Chat Model** - Claude 4 Sonnet language model
5. **MCP Client** - Connects to your MCP server with Bearer auth
6. **Respond to Webhook** - Returns results to caller

### Node Connections

```
Webhook → Server Auth → AI Agent → Respond to Webhook
                            ↑
                    MCP Client (tools)
                            ↑
                 Anthropic Chat Model (LLM)
```

## Important Features

### UUID Randomization

Every call to `generateAgentWorkflow()` generates unique UUIDs for:
- All node IDs
- Webhook ID
- Instance ID in metadata

This ensures no conflicts when creating multiple workflows.

### Configurable Parameters

The workflow nodes use n8n expressions to dynamically read from webhook body:
- `system_prompt` - AI system instructions
- `user_input` - User's request/question
- `token` - User authentication token
- `tenant_id` - Encrypted tenant identifier

### Example Webhook Request

```json
POST https://n8n.example.com/webhook/{webhook-id}

{
  "system_prompt": "You are a helpful assistant for project management.",
  "user_input": "Create a new task for the website redesign project",
  "token": "user-bearer-token",
  "tenant_id": "encrypted-tenant-hash"
}
```

## Testing

The service includes comprehensive unit tests covering:
- Valid workflow structure generation
- MCP credential configuration
- MCP endpoint URL configuration
- AI credential handling (custom and default)
- APP_URL configuration (custom and default)
- UUID randomization
- Node connections integrity

Run tests:
```bash
php artisan test --filter=N8NServiceTest
```

## Configuration Requirements

Ensure these values are set in your `.env` file:

```env
# N8n API Configuration
N8N_API_URL=https://n8n.example.com/api/v1
N8N_API_KEY=your-n8n-api-key

# N8n Webhook Secret
N8N_SECRET=supersecret

# Application URL (used for decrypt-tenant endpoint)
APP_URL=https://app.example.com
```

## Related Files

- `app/Services/N8NService.php` - Service implementation
- `tests/Unit/Services/N8NServiceTest.php` - Comprehensive tests
- `config/services.php` - N8n configuration
- `agent_tmpl.json` - Original workflow template reference
