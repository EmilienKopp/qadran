# Voice Assistant Token & Credentials Issues - Sanity Check

## Date: November 9, 2025
## Status: ✅ RESOLVED

## Critical Issues Found & Fixed

### 1. ✅ **FIXED: Missing Tenant Host Information in MCP Requests**

**Problem:** The MCP Client node in n8n was calling the MCP endpoint without any tenant identification:
- No tenant host in URL
- No `X-Tenant-Host` header
- No tenant information in token format
- When using `host.docker.internal`, subdomain information was lost

**Impact:** MCP middleware couldn't resolve which tenant the request belonged to, causing authentication failures.

**Solution:**
1. Append tenant host as query parameter to MCP endpoint URL: `/mcp/qadran?tenant={tenant.host}`
2. Updated `TenantAwareMcp` middleware to check for `?tenant=` query parameter first
3. Falls back to other resolution methods (headers, token format, subdomain) if needed

**Files Changed:**
- `app/Http/Controllers/VoiceAssistantController.php` line ~70: Append `?tenant=` to URL
- `app/Http/Middleware/TenantAwareMcp.php` line ~68: Check query parameter first

### 2. ✅ **FIXED: Authorization Header Typo in Server Auth Node**

**Location:** `VoiceAssistantController.php` line 79-85

```php
$token = $user->createToken($tokenName, [
    'mcp:use',
    'mcp:tools',
    'mcp:resources',
    'mcp:prompts',
], now()->addYear());
```

**Problem:** The `createToken()` method returns a `NewAccessToken` object that contains:
- `$token->accessToken` - The PersonalAccessToken model (with hashed token)
- `$token->plainTextToken` - The actual bearer token string (only available once)

**Current Code (Line 103-106):**
```php
$mcpCredResult = $this->n8nService->createMcpCredentials(
    $mcpCredentialName,
    [
        'token' => $token->plainTextToken,  // ✅ CORRECT - Uses plainTextToken
    ]
);
```

This part is actually CORRECT.

### 2. **CRITICAL: Missing Import Statement**

**Location:** `VoiceAssistantController.php` line 68

```php
if(Str::contains($route,'localhost')) {
```

**Error from logs:**
```
Class "App\Http\Controllers\Str" not found at /var/www/html/app/Http/Controllers/VoiceAssistantController.php:68
```

**Problem:** Missing `use Illuminate\Support\Str;` at the top of the file. It's used at line 15 but not imported.

**Impact:** The entire activation flow fails before reaching token creation, preventing any workflow from being created.

### 3. **POTENTIAL ISSUE: Token Retrieval in Workflow Execution**

**Location:** `VoiceAssistantController.php` line 448

```php
'token' => $user->tokens()->first()?->token,
```

**Problem:** This retrieves the **hashed token** from the database, not the plain text token. The MCP middleware expects the plain text bearer token.

**Why this fails:**
- Sanctum stores only **hashed** tokens in the database for security
- `$token->token` returns the hashed value (e.g., SHA-256 hash)
- The plain text token (`$token->plainTextToken`) is only available immediately after creation
- The MCP endpoint needs the **plain text token** to authenticate

**Current workflow:**
1. User speaks → Audio transcribed
2. `sendToAssistantWorkflow()` sends request with `$user->tokens()->first()->token` (HASHED)
3. N8n workflow calls MCP endpoint with hashed token
4. MCP middleware tries to find token using `PersonalAccessToken::findToken($hashedToken)`
5. ❌ FAILS because `findToken()` expects plain text and hashes it to search

### 4. **ARCHITECTURAL ISSUE: Bearer Token in n8n Credentials**

**Location:** `N8NService.php` line 38-50

```php
public function createMcpCredentials(string $name, array $credentials)
{
    $response = $this->httpClient->post("{$this->apiUrl}/credentials", [
        'headers' => [
            'X-N8N-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'name' => $name,
            'type' => 'httpBearerAuth',
            'data' => $credentials,  // Contains 'token' => plainTextToken
        ],
    ]);
}
```

**Current workflow:**
1. Plain text token stored in n8n credentials (✅ CORRECT)
2. MCP Client node uses these credentials to authenticate
3. n8n sends `Authorization: Bearer {plainTextToken}` to MCP endpoint

**Verification needed:** Check if the token in n8n credentials is still valid and matches expected format.

### 5. **ISSUE: Token Format in Workflow Webhook**

**Location:** Line 448 - Token sent in webhook payload

The workflow sends a `token` field in the webhook body:
```json
{
  "timestamp": "...",
  "token": "hashed_token_from_database",  // ❌ WRONG
  "system_prompt": "...",
  "user_input": "...",
  "tenant_id": "..."
}
```

But the MCP authentication happens via **Authorization header**, not the webhook body token.

### 6. **MCP Middleware Token Validation**

**Location:** `TenantAwareMcp.php` line 119-133

```php
protected function authenticateUser(Request $request): ?\App\Models\User
{
    $token = $request->get('_mcp_actual_token');
    
    if (! $token) {
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);  // Extracts plain text token
        }
    }
    
    $accessToken = PersonalAccessToken::findToken($token);  // Hashes and searches
    
    if ($accessToken && $accessToken->can('mcp:use')) {
        return $accessToken->tokenable;
    }
}
```

This is **CORRECT** - it expects a plain text token in the Authorization header.

## Root Cause Analysis

### Why MCP Connection Fails

The most likely reason for "Can't connect" error:

1. ✅ **Token creation:** Plain text token created and stored in n8n (CORRECT)
2. ❓ **Token in n8n:** Need to verify the token stored in n8n credentials is correct
3. ❓ **MCP endpoint URL:** Need to verify the URL is reachable from n8n
4. ❌ **Missing Str import:** Code fails at line 68 before workflow creation

## Recommended Fixes

### Fix 1: Add Missing Import (CRITICAL)

**File:** `app/Http/Controllers/VoiceAssistantController.php`

Add at the top:
```php
use Illuminate\Support\Str;
```

### Fix 2: Remove Unused Token from Webhook Payload

The `token` field in line 448 is not used by MCP (uses Authorization header instead). If it's needed for the Server Auth node, keep it but document its purpose.

### Fix 3: Add Debug Logging

Add logging to verify token flow:

```php
// In activate() after creating token
Log::info('Token created for MCP', [
    'user_id' => $user->id,
    'token_length' => strlen($token->plainTextToken),
    'token_preview' => substr($token->plainTextToken, 0, 10) . '...',
]);

// In sendToAssistantWorkflow()
Log::info('Sending to workflow', [
    'webhook_url' => $webhookUrl,
    'has_token' => !empty($user->tokens()->first()),
]);
```

### Fix 4: Verify n8n Credential Storage

Check n8n API to verify the credential was created correctly:

```bash
curl -X GET "http://n8n:5678/api/v1/credentials/{CREDENTIAL_ID}" \
  -H "X-N8N-API-KEY: your-api-key"
```

### Fix 5: Test MCP Endpoint Directly

Test the MCP endpoint with the token:

```bash
curl -X POST "http://your-app/mcp/qadran" \
  -H "Authorization: Bearer {PLAIN_TEXT_TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc": "2.0", "method": "initialize", "id": 1}'
```

## Testing Checklist

- [ ] Fix missing Str import
- [ ] Verify token is created successfully
- [ ] Verify token is stored in n8n credentials
- [ ] Verify MCP endpoint URL is correct and reachable from n8n
- [ ] Test MCP endpoint with plain text token directly
- [ ] Check n8n workflow execution logs
- [ ] Verify MCP Client node configuration in n8n
- [ ] Check if tenant context is correctly set

## Questions to Answer

1. **Is the MCP endpoint URL reachable from n8n?**
   - If n8n is in Docker, does it use `host.docker.internal`?
   - Is the port correct?

2. **Is the token format correct in n8n?**
   - Check n8n credentials API
   - Verify it's not double-encoded or modified

3. **Does the MCP endpoint respond at all?**
   - Check Laravel logs for incoming MCP requests
   - Check if middleware is even reached

4. **Is the credential ID correct?**
   - Verify `$mcpCredentials->id` matches the actual n8n credential ID
   - Check if credential was deleted/recreated

## Next Steps

1. **Immediate:** Fix the missing `Str` import
2. **Verify:** Check if the token in n8n credentials is correct
3. **Test:** Make a direct HTTP request to the MCP endpoint
4. **Debug:** Add detailed logging throughout the flow
5. **Monitor:** Check both Laravel and n8n logs during activation
