<?php

namespace App\Http\Middleware;

use App\Models\Landlord\Tenant;
use App\Services\TenantFinder;
use App\Support\RequestContextResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class TenantAwareMcp
{
    /**
     * Handle an incoming request.
     * 
     * This middleware provides tenant-aware authentication for MCP servers by:
     * 1. Extracting tenant information from Authorization header or host
     * 2. Setting the current tenant context
     * 3. Authenticating the user within the tenant scope
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::debug('🔧 TenantAwareMcp middleware processing request', [
            'host' => $request->getHost(),
            'authorization' => $request->hasHeader('Authorization') ? 'present' : 'missing',
            'user_agent' => $request->userAgent(),
        ]);

        // Extract tenant information from the request
        $tenant = $this->resolveTenant($request);
        
        if (!$tenant) {
            return response()->json([
                'error' => 'Tenant not found. Please include tenant information in the Authorization header or ensure proper host configuration.',
                'code' => 'TENANT_NOT_FOUND'
            ], 400);
        }

        // Set the current tenant context
        $tenant->makeCurrent();
        Log::debug('🏢 Tenant set as current', ['tenant' => $tenant->name, 'host' => $tenant->host]);

        // Set the tenant auth guard as default for this request
        config(['auth.defaults.guard' => 'tenant']);
        
        // Attempt to authenticate the user using the token
        $user = $this->authenticateUser($request);
        
        if (!$user) {
            return response()->json([
                'error' => 'Authentication failed. Please provide a valid access token for the tenant.',
                'code' => 'AUTH_FAILED',
                'tenant' => $tenant->host
            ], 401);
        }

        Log::debug('👤 User authenticated for MCP request', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'tenant' => $tenant->host
        ]);

        // Set the authenticated user for this request
        Auth::guard('tenant')->setUser($user);
        
        return $next($request);
    }

    /**
     * Resolve the tenant from the request.
     */
    protected function resolveTenant(Request $request): ?Tenant
    {
        // First, try to extract tenant from Authorization header
        // Format: "Bearer tenant:host:token" or "Bearer token" (with host in header)
        $authHeader = $request->header('Authorization');
        
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            
            // Check if token contains tenant information (tenant:host:actual_token)
            if (str_contains($token, ':')) {
                $parts = explode(':', $token, 3);
                if (count($parts) >= 2) {
                    $tenantHost = $parts[1];
                    
                    // Store the actual token for later authentication
                    $request->merge(['_mcp_actual_token' => $parts[2] ?? $parts[1]]);
                    
                    return Tenant::where('host', $tenantHost)->first();
                }
            }
        }

        // Fallback to header-based tenant identification
        $tenantHost = $request->header('X-Tenant-Host') ?: $request->header('X-MCP-Tenant');
        
        if ($tenantHost) {
            return Tenant::where('host', $tenantHost)->first();
        }

        // Fallback to host-based resolution (for subdomain routing)
        $tenantFinder = app(TenantFinder::class);
        return $tenantFinder->findForRequest($request);
    }

    /**
     * Authenticate the user using the provided token.
     */
    protected function authenticateUser(Request $request): ?\App\Models\User
    {
        // Extract the actual token (might be modified by resolveTenant)
        $token = $request->get('_mcp_actual_token');
        
        if (!$token) {
            $authHeader = $request->header('Authorization');
            if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
                $token = substr($authHeader, 7);
            }
        }

        if (!$token) {
            Log::debug('No authentication token found');
            return null;
        }

        // Try Sanctum authentication first
        $accessToken = PersonalAccessToken::findToken($token);
        
        if ($accessToken && !$accessToken->tokenable instanceof \App\Models\Landlord\User) {
            // This is a tenant user token
            $user = $accessToken->tokenable;
            
            // Verify the token is valid
            if ($accessToken->can('mcp:use') || $accessToken->can('*')) {
                Log::debug('User authenticated via Sanctum token', [
                    'user_id' => $user->id,
                    'token_name' => $accessToken->name
                ]);
                return $user;
            }
        }

        // You could add additional authentication methods here
        // For example, session-based auth for same-domain requests
        
        Log::debug('Authentication failed - no valid token found');
        return null;
    }
}