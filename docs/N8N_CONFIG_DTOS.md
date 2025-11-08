# N8n Configuration DTOs

## Overview

The N8n configuration system uses Data Transfer Objects (DTOs) to enforce structure for JSONB columns in the database. This provides type safety, validation, and easy serialization/deserialization.

## DTOs

### N8nConfig

The main configuration DTO that stores all N8n integration settings.

**Location:** `app/DTOs/N8nConfig.php`

**Properties:**
- `?N8nCredentials $aiCredentials` - AI provider credentials (Anthropic, OpenAI, etc.)
- `?N8nCredentials $mcpCredentials` - MCP Bearer token credentials
- `?string $mcpEndpointUrl` - Full URL to the MCP endpoint
- `?string $workflowId` - N8n workflow ID for this tenant/user
- `?string $webhookUrl` - Full webhook URL after workflow creation
- `?array $preferences` - Additional workflow preferences (model, timeout, etc.)

### N8nCredentials

Stores credential references for n8n.

**Location:** `app/DTOs/N8nCredentials.php`

**Properties:**
- `string $id` - Credential ID in n8n
- `string $name` - Credential name/label in n8n

## Database Schema

### Landlord Database - `tenants` Table

```sql
ALTER TABLE tenants ADD COLUMN n8n_config JSONB NULL 
  COMMENT 'N8n integration configuration including AI credentials, MCP settings, and workflow preferences';
```

### Tenant Database - `users` Table

```sql
ALTER TABLE users ADD COLUMN n8n_config JSONB NULL 
  COMMENT 'User-specific N8n integration configuration that overrides tenant-wide settings';
```

## Eloquent Models

Both `Tenant` and `User` models have the `n8n_config` attribute with automatic casting.

### Tenant Model

```php
use App\DTOs\N8nConfig;
use App\Models\Landlord\Tenant;

$tenant = Tenant::find($id);

// Access as N8nConfig object
$config = $tenant->n8n_config; // Returns N8nConfig|null

// Set configuration
$tenant->n8n_config = new N8nConfig(
    mcpCredentials: new N8nCredentials('cred-id', 'MCP Token'),
    mcpEndpointUrl: 'https://app.example.com/mcp/tenant',
    workflowId: 'workflow-123',
    webhookUrl: 'https://n8n.example.com/webhook/abc-def'
);

$tenant->save();
```

### User Model

```php
use App\DTOs\N8nConfig;
use App\Models\User;

$user = User::find($id);

// Access user-specific config (if set)
$userConfig = $user->n8n_config;

// Access merged config (tenant defaults + user overrides)
$effectiveConfig = $tenant->n8n_config?->mergeWith($user->n8n_config);
```

## Usage Examples

### Creating Configuration from Scratch

```php
use App\DTOs\N8nConfig;
use App\DTOs\N8nCredentials;

$config = new N8nConfig(
    aiCredentials: new N8nCredentials(
        id: 'V3lx69vlrI5hqH8S',
        name: 'Anthropic account'
    ),
    mcpCredentials: new N8nCredentials(
        id: 'OTH6Ir7n6n5TXHSN',
        name: 'Qadran MCP Bearer'
    ),
    mcpEndpointUrl: 'http://host.docker.internal:8000/mcp/qadran',
    preferences: [
        'model' => 'claude-sonnet-4-20250514',
        'timeout' => 30,
        'max_tokens' => 4096,
    ]
);
```

### Loading from Array

```php
use App\DTOs\N8nConfig;

$config = N8nConfig::fromArray([
    'ai_credentials' => [
        'id' => 'V3lx69vlrI5hqH8S',
        'name' => 'Anthropic account'
    ],
    'mcp_credentials' => [
        'id' => 'OTH6Ir7n6n5TXHSN',
        'name' => 'Qadran MCP Bearer'
    ],
    'mcp_endpoint_url' => 'http://host.docker.internal:8000/mcp/qadran',
    'workflow_id' => 'workflow-123',
    'webhook_url' => 'https://n8n.example.com/webhook/abc',
    'preferences' => [
        'model' => 'claude-sonnet-4-20250514',
    ]
]);
```

### Merging Configurations (Tenant + User Override)

```php
// Tenant has default config
$tenantConfig = $tenant->n8n_config;

// User wants custom AI credentials
$userConfig = new N8nConfig(
    aiCredentials: new N8nCredentials('user-ai-id', 'User AI Account')
);

// Merge: user overrides take precedence
$effectiveConfig = $tenantConfig->mergeWith($userConfig);

// Use the effective config
if ($effectiveConfig->isValid()) {
    $workflow = $n8nService->generateAgentWorkflow(
        mcpCredentials: $effectiveConfig->getMcpCredentialsArray(),
        mcpEndpointUrl: $effectiveConfig->mcpEndpointUrl,
        aiCredentials: $effectiveConfig->getAiCredentialsArray(),
    );
}
```

### Working with Preferences

```php
$config = $tenant->n8n_config;

// Get preference with default
$model = $config->getPreference('model', 'claude-sonnet-4-20250514');
$timeout = $config->getPreference('timeout', 30);

// Set preference
$config->setPreference('max_tokens', 8192);
$config->setPreference('temperature', 0.7);

$tenant->n8n_config = $config;
$tenant->save();
```

### Validation

```php
$config = $tenant->n8n_config;

// Check if config is valid (has required fields)
if ($config && $config->isValid()) {
    // Config has MCP credentials and endpoint URL
    $workflow = $n8nService->generateAgentWorkflow(
        mcpCredentials: $config->getMcpCredentialsArray(),
        mcpEndpointUrl: $config->mcpEndpointUrl,
    );
} else {
    // Config is incomplete, show setup wizard
    return redirect()->route('n8n.setup');
}

// Check if credentials are valid
if ($config->mcpCredentials?->isValid()) {
    // Credentials have both id and name
}
```

### JSON Serialization

```php
$config = new N8nConfig(/* ... */);

// Serialize to JSON string
$json = $config->toJson();

// Serialize to array
$array = $config->jsonSerialize();

// Load from JSON string
$config = N8nConfig::fromJson($json);
```

## Helper Methods

### N8nConfig Methods

- `isValid(): bool` - Check if config has minimum required fields (MCP credentials and endpoint)
- `getAiCredentialsArray(): ?array` - Get AI credentials formatted for N8NService
- `getMcpCredentialsArray(): ?array` - Get MCP credentials formatted for N8NService
- `getPreference(string $key, mixed $default = null): mixed` - Get preference value with fallback
- `setPreference(string $key, mixed $value): self` - Set preference value (chainable)
- `mergeWith(?N8nConfig $override): N8nConfig` - Merge with another config (for user overrides)
- `toJson(): string` - Convert to JSON string for database storage
- `fromJson(?string $json): ?N8nConfig` - Create from JSON string
- `fromArray(array $data): N8nConfig` - Create from array

### N8nCredentials Methods

- `isValid(): bool` - Check if credentials have both id and name
- `toArray(): array` - Convert to array format expected by N8NService
- `fromArray(array $data): N8nCredentials` - Create from array

## Complete Example: Setting Up N8n for a Tenant

```php
use App\DTOs\N8nConfig;
use App\DTOs\N8nCredentials;
use App\Models\Landlord\Tenant;
use App\Services\N8NService;

// 1. Create or load tenant
$tenant = Tenant::find($tenantId);

// 2. Configure N8n settings
$config = new N8nConfig(
    aiCredentials: new N8nCredentials(
        id: config('services.n8n.default_ai_credential_id'),
        name: 'Tenant Anthropic Account'
    ),
    mcpCredentials: new N8nCredentials(
        id: config('services.n8n.default_mcp_credential_id'),
        name: "MCP - {$tenant->name}"
    ),
    mcpEndpointUrl: route('mcp.tenant', ['tenant' => $tenant->host]),
    preferences: [
        'model' => 'claude-sonnet-4-20250514',
        'timeout' => 30,
        'enable_thinking' => true,
    ]
);

// 3. Generate workflow
$n8nService = app(N8NService::class);
$workflow = $n8nService->generateAgentWorkflow(
    mcpCredentials: $config->getMcpCredentialsArray(),
    mcpEndpointUrl: $config->mcpEndpointUrl,
    aiCredentials: $config->getAiCredentialsArray(),
);

// 4. Create workflow in n8n
$created = $n8nService->createWorkflow($workflow);

// 5. Store workflow details
$config->workflowId = $created['id'];
$config->webhookUrl = $created['webhookUrl'] ?? null;

$tenant->n8n_config = $config;
$tenant->save();

echo "✓ N8n workflow created for {$tenant->name}\n";
echo "  Workflow ID: {$config->workflowId}\n";
echo "  Webhook URL: {$config->webhookUrl}\n";
```

## Database Structure Example

### Tenant Config (JSON in database)

```json
{
  "ai_credentials": {
    "id": "V3lx69vlrI5hqH8S",
    "name": "Anthropic account"
  },
  "mcp_credentials": {
    "id": "OTH6Ir7n6n5TXHSN",
    "name": "Qadran MCP Bearer"
  },
  "mcp_endpoint_url": "http://host.docker.internal:8000/mcp/qadran",
  "workflow_id": "workflow-abc-123",
  "webhook_url": "https://n8n.example.com/webhook/xyz-789",
  "preferences": {
    "model": "claude-sonnet-4-20250514",
    "timeout": 30,
    "max_tokens": 4096
  }
}
```

### User Override Config (JSON in database)

```json
{
  "ai_credentials": {
    "id": "user-custom-ai-id",
    "name": "My Personal AI Account"
  },
  "preferences": {
    "temperature": 0.7,
    "max_tokens": 8192
  }
}
```

## Testing

```php
use App\DTOs\N8nConfig;
use App\DTOs\N8nCredentials;
use Tests\TestCase;

class N8nConfigTest extends TestCase
{
    public function test_can_create_config_from_array(): void
    {
        $data = [
            'mcp_credentials' => ['id' => 'test', 'name' => 'Test'],
            'mcp_endpoint_url' => 'http://localhost/mcp/test',
        ];
        
        $config = N8nConfig::fromArray($data);
        
        $this->assertInstanceOf(N8nConfig::class, $config);
        $this->assertEquals('test', $config->mcpCredentials->id);
    }
    
    public function test_can_merge_configs(): void
    {
        $base = new N8nConfig(
            mcpCredentials: new N8nCredentials('mcp-1', 'MCP'),
            mcpEndpointUrl: 'http://localhost/mcp'
        );
        
        $override = new N8nConfig(
            aiCredentials: new N8nCredentials('ai-1', 'AI')
        );
        
        $merged = $base->mergeWith($override);
        
        $this->assertNotNull($merged->mcpCredentials);
        $this->assertNotNull($merged->aiCredentials);
    }
}
```

## Related Files

- `app/DTOs/N8nConfig.php` - Main configuration DTO
- `app/DTOs/N8nCredentials.php` - Credentials DTO
- `app/Casts/N8nConfigCast.php` - Eloquent cast for automatic serialization
- `app/Models/Landlord/Tenant.php` - Landlord tenant model
- `app/Models/User.php` - Tenant user model
- `database/migrations/landlord/2025_11_08_172312_add_n8n_config_to_tenants_table.php` - Landlord migration
- `database/migrations/2025_11_08_172342_add_n8n_config_to_users_table.php` - Tenant migration
