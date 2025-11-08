<?php

namespace App\Mcp\Tools;

use App\Models\Landlord\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

abstract class TenantAwareTool extends Tool
{
    /**
     * The authenticated user for this request.
     */
    protected ?User $user = null;

    /**
     * The current tenant for this request.
     */
    protected ?Tenant $tenant = null;

    /**
     * Handle the tool request with tenant and user context.
     */
    final public function handle(Request $request): Response
    {
        // Initialize tenant and user context
        $this->initializeTenantContext($request);
        
        // Verify we have proper context
        if (!$this->tenant) {
            return Response::error('No tenant context found. Please ensure you are authenticated to a specific tenant.');
        }

        if (!$this->user) {
            return Response::error('No authenticated user found. Please ensure you are properly authenticated.');
        }

        Log::debug('🔧 MCP Tool executing with context', [
            'tool' => static::class,
            'user_id' => $this->user->id,
            'tenant' => $this->tenant->host,
            'inputs' => $this->sanitizeInputsForLog($request->all())
        ]);

        try {
            return $this->handleTenantRequest($request);
        } catch (\Exception $e) {
            Log::error('MCP Tool execution failed', [
                'tool' => static::class,
                'user_id' => $this->user->id,
                'tenant' => $this->tenant->host,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Response::error('Tool execution failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle the tool request. Override this method in your tool implementations.
     * 
     * You can access:
     * - $this->user (authenticated User model)
     * - $this->tenant (current Tenant model)
     * - All user and tenant context is already set up
     */
    abstract protected function handleTenantRequest(Request $request): Response|array;

    /**
     * Initialize tenant and user context from the authenticated request.
     */
    protected function initializeTenantContext(Request $request): void
    {
        // Get the current tenant (should be set by middleware)
        $this->tenant = Tenant::current();
        
        // Get the authenticated user (should be set by middleware)
        $this->user = Auth::guard('tenant')->user();
        
        // Additional context logging
        if ($this->tenant && $this->user) {
            Log::debug('Tenant context initialized', [
                'tenant_id' => $this->tenant->id,
                'tenant_host' => $this->tenant->host,
                'user_id' => $this->user->id,
                'user_email' => $this->user->email
            ]);
        }
    }

    /**
     * Check if the current user has a specific permission.
     */
    protected function userCan(string $permission): bool
    {
        return $this->user?->can($permission) ?? false;
    }

    /**
     * Check if the current user has any of the specified roles.
     */
    protected function userHasRole(string|array $roles): bool
    {
        if (!$this->user) {
            return false;
        }

        $roles = is_array($roles) ? $roles : [$roles];
        
        foreach ($roles as $role) {
            if ($this->user->hasRole($role)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get the current user's organizations.
     */
    protected function getUserOrganizations()
    {
        return $this->user?->organizations ?? collect();
    }

    /**
     * Create an error response with tenant context.
     */
    protected function errorResponse(string $message, array $context = []): Response
    {
        $contextInfo = array_merge([
            'tenant' => $this->tenant?->host,
            'user_id' => $this->user?->id,
        ], $context);
        
        Log::warning('MCP Tool error response', [
            'tool' => static::class,
            'message' => $message,
            'context' => $contextInfo
        ]);
        
        return Response::error($message);
    }

    /**
     * Create a success response with optional resource information.
     */
    protected function successResponse(string $message, array $resource = null): Response
    {
        if ($resource) {
            // Return multiple responses when we have both text and resource
            return Response::text($message);
        }

        return Response::text($message);
    }

    /**
     * Create a response with both text and resource information.
     */
    protected function textWithResource(string $message, array $resource): array
    {
        return [
            Response::text($message),
            Response::text("Resource: {$resource['uri']}")
        ];
    }

    /**
     * Sanitize inputs for logging (remove sensitive data).
     */
    protected function sanitizeInputsForLog(array $inputs): array
    {
        $sensitiveKeys = ['password', 'token', 'secret', 'key', 'authorization'];
        
        $sanitized = [];
        foreach ($inputs as $key => $value) {
            if (in_array(strtolower($key), $sensitiveKeys)) {
                $sanitized[$key] = '[REDACTED]';
            } elseif (is_string($value) && strlen($value) > 100) {
                $sanitized[$key] = substr($value, 0, 100) . '... [TRUNCATED]';
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }

    /**
     * Helper method to format model data for MCP resource responses.
     */
    protected function formatModelResource(string $type, $model, string $uriPrefix = null): array
    {
        $uriPrefix = $uriPrefix ?: strtolower($type);
        
        return [
            'uri' => "{$uriPrefix}://{$model->id}",
            'name' => $model->name ?? "#{$model->id}",
            'mimeType' => 'application/json',
            'description' => $model->description ?? "A {$type} record"
        ];
    }
}