# Tenant-Aware MCP Server Authentication

This document describes the tenant-aware authentication system for the Qadran MCP (Model Context Protocol) server.

## Overview

The Qadran MCP server operates in a multi-tenant environment where each tenant has its own isolated data and users. The authentication flow ensures that MCP clients can only access data within their authenticated tenant context.

## Architecture Components

### 1. TenantAwareMcp Middleware (`app/Http/Middleware/TenantAwareMcp.php`)

This middleware handles:
- **Tenant Resolution**: Extracts tenant information from Authorization header or host
- **Tenant Context**: Sets the current tenant using Spatie Multitenancy
- **User Authentication**: Validates access tokens and authenticates users within tenant scope
- **Authorization**: Ensures users can only access their tenant's data

### 2. TenantAwareTool Base Class (`app/Mcp/Tools/TenantAwareTool.php`)

All MCP tools should extend this base class, which provides:
- Automatic tenant and user context initialization
- Permission checking helpers (`userCan()`, `userHasRole()`)
- Error handling with tenant context
- Logging with tenant information

### 3. Updated QadranServer Registration (`routes/ai.php`)

The MCP server is registered with tenant-aware middleware stack.

## Authentication Flow

### 1. Token-Based Authentication

MCP clients authenticate using one of these methods:

#### Method A: Tenant in Token Format
```
Authorization: Bearer tenant:TENANT_HOST:ACCESS_TOKEN
```

Example:
```
Authorization: Bearer tenant:acme:1|abc123def456...
```

#### Method B: Separate Headers
```
Authorization: Bearer ACCESS_TOKEN
X-Tenant-Host: TENANT_HOST
```

Example:
```
Authorization: Bearer 1|abc123def456...
X-Tenant-Host: acme
```

#### Method C: Subdomain Routing (Production)
```
Host: acme.qadran.io
Authorization: Bearer ACCESS_TOKEN
```

### 2. Token Requirements

Access tokens must:
- Be valid Laravel Sanctum personal access tokens
- Belong to a user within the specified tenant
- Have `mcp:use` capability or wildcard (`*`) permission
- Be associated with a tenant user (not landlord user)

## Client Setup Guide

### For MCP Client Developers

1. **Obtain Access Token**
   ```bash
   # User must first authenticate via your tenant's web interface
   # and generate an API token with MCP access
   ```

2. **Configure Client**
   ```json
   {
     "mcpServers": {
       "qadran": {
         "command": "curl",
         "args": [
           "-X", "POST",
           "https://api.qadran.io/mcp/qadran",
           "-H", "Content-Type: application/json",
           "-H", "Authorization: Bearer tenant:YOUR_TENANT:YOUR_ACCESS_TOKEN"
         ]
       }
     }
   }
   ```

3. **Alternative Configuration (Separate Headers)**
   ```json
   {
     "mcpServers": {
       "qadran": {
         "command": "curl",
         "args": [
           "-X", "POST", 
           "https://api.qadran.io/mcp/qadran",
           "-H", "Content-Type: application/json",
           "-H", "Authorization: Bearer YOUR_ACCESS_TOKEN",
           "-H", "X-Tenant-Host: YOUR_TENANT"
         ]
       }
     }
   }
   ```

### For Claude Desktop

Add to your Claude Desktop configuration:

```json
{
  "mcpServers": {
    "qadran": {
      "command": "curl",
      "args": [
        "-X", "POST",
        "https://api.qadran.io/mcp/qadran", 
        "-H", "Content-Type: application/json",
        "-H", "Authorization: Bearer tenant:YOUR_TENANT:YOUR_ACCESS_TOKEN",
        "-d", "@-"
      ]
    }
  }
}
```

## Token Generation

Users can generate MCP access tokens through the Qadran web interface:

1. Log into your tenant: `https://YOUR_TENANT.qadran.io/login`
2. Go to Profile Settings → API Tokens
3. Create new token with "MCP Access" permission
4. Copy the generated token for use in your MCP client

## Environment-Specific URLs

### Production
- **Non-staging**: `https://api.qadran.io/mcp/qadran`
- **Staging**: `https://qadran.io/mcp/qadran`

### Local Development
- **URL**: `http://localhost:8000/mcp/qadran`
- **Default Tenant**: `qadranio` (auto-selected in local env)

## Security Features

### 1. Tenant Isolation
- Each tenant's data is completely isolated
- Users can only access their own tenant's data
- Cross-tenant data access is impossible

### 2. User Permissions
- Tools can check user permissions using `$this->userCan('permission')`
- Role-based access control via `$this->userHasRole(['admin', 'user'])`
- Organization-level access control

### 3. Rate Limiting
- MCP requests are rate-limited per tenant
- Prevents abuse and ensures fair resource usage

### 4. Audit Logging
- All MCP tool executions are logged with user and tenant context
- Failed authentication attempts are tracked
- Sensitive data is automatically redacted from logs

## Troubleshooting

### Common Issues

#### 1. "Tenant not found"
- Verify tenant host/domain is correct
- Ensure tenant exists and is active
- Check header format in authorization

#### 2. "Authentication failed"
- Verify access token is valid and not expired
- Ensure token has `mcp:use` permission
- Check token belongs to correct tenant user

#### 3. "No tenant context found"
- Middleware may not be properly configured
- Check route registration in `routes/ai.php`
- Verify tenant finder is working

#### 4. "Permission denied"
- User lacks required permissions for the tool
- Check user roles and permissions in tenant
- Verify organization membership if required

## Example Tool Implementation

```php
<?php

namespace App\Mcp\Tools;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;

class ExampleTool extends TenantAwareTool
{
    protected string $description = 'Example tenant-aware tool';

    protected function handleTenantRequest(Request $request): Response
    {
        // Automatic tenant and user context available:
        // $this->tenant - Current tenant model
        // $this->user - Authenticated user model
        
        // Check permissions
        if (!$this->userCan('use-example-tool')) {
            return $this->errorResponse('Permission denied');
        }
        
        // Access user's organizations
        $organizations = $this->getUserOrganizations();
        
        // Perform tenant-scoped operations
        $data = SomeModel::where('user_id', $this->user->id)->get();
        
        return $this->successResponse(
            "Tool executed successfully for {$this->user->email} in {$this->tenant->host}"
        );
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'example_param' => $schema->string()->description('Example parameter')
        ];
    }
}
```

## Testing

### Unit Tests

```php
public function test_tenant_aware_tool()
{
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();
    
    $tenant->makeCurrent();
    
    $response = QadranServer::actingAs($user)->tool(ExampleTool::class, [
        'example_param' => 'test'
    ]);
    
    $response->assertOk();
}
```

### Integration Testing

Use the MCP Inspector to test authentication:

```bash
php artisan mcp:inspector qadran
```

## Migration from Non-Tenant-Aware

If you have existing MCP tools that need to be made tenant-aware:

1. **Change Base Class**
   ```php
   // Before
   class MyTool extends Tool
   
   // After  
   class MyTool extends TenantAwareTool
   ```

2. **Update Handle Method**
   ```php
   // Before
   public function handle(Request $request): Response
   
   // After
   protected function handleTenantRequest(Request $request): Response
   ```

3. **Add Permission Checks**
   ```php
   if (!$this->userCan('required-permission')) {
       return $this->errorResponse('Permission denied');
   }
   ```

4. **Use Tenant Context**
   ```php
   // Access current user
   $userId = $this->user->id;
   
   // Access current tenant
   $tenantHost = $this->tenant->host;
   ```

This tenant-aware system ensures that your MCP server operates securely within your multi-tenant architecture while providing a seamless experience for authenticated users.