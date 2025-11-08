<?php

namespace App\Http\Middleware;

use App\Models\Landlord\Tenant;
use App\Models\PersonalAccessToken;
use App\Services\TenantFinder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TenantAwareMcp
{
    private $parsedHeader = null;


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

        $tenant = $this->resolveTenant($request);

        if (! $tenant) {
            return response()->json([
                'error' => 'Tenant not found. Please include tenant information in the Authorization header or ensure proper host configuration.',
                'code' => 'TENANT_NOT_FOUND',
            ], 400);
        }

        $tenant->makeCurrent();
        Log::debug('🏢 Tenant set as current', ['tenant' => $tenant->name, 'host' => $tenant->host]);

        // Set the tenant auth guard as default for this request
        config(['auth.defaults.guard' => 'tenant']);

        $user = $this->authenticateUser($request);

        \Log::debug('👤 User authentication attempt result', [
            'user_found' => $user ? true : false,
            'tenant' => $tenant->host,
        ]);

        if (! $user) {
            return response()->json([
                'error' => 'Authentication failed. Please provide a valid access token for the tenant.',
                'code' => 'AUTH_FAILED',
                'tenant' => $tenant->host,
                'requested_token' => $request->header('Authorization'),
                'req' => $request->all(),
            ], 401);
        }

        Auth::guard('tenant')->setUser($user);

        return $next($request);
    }

    /**
     * Resolve the tenant from the request.
     */
    protected function resolveTenant(Request $request): ?Tenant
    {
        $authHeader = $request->header('Authorization');

        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);

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
        $tenantHost = $request->header('X-Tenant-Host');

        if ($tenantHost) {
            return Tenant::where('host', $tenantHost)->first();
        } else if ($request->header('X-MCP-Tenant')) {
            $value = $request->header('X-MCP-Tenant');
            $parsed = $this->parseMCPTenantHeader($value);
            $tenantHost = $parsed->host;
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

        if (! $token) {
            $authHeader = $request->header('Authorization');
            if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
                $token = substr($authHeader, 7);
            } else if ($this->parsedHeader && $this->parsedHeader->token) {
                $token = $this->parsedHeader->token;
            }
        }

        if (! $token) {
            Log::debug('No authentication token found');

            return null;
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if ($accessToken && ! $accessToken->tokenable instanceof \App\Models\Landlord\User) {
            $user = $accessToken->tokenable;

            if ($accessToken->can('mcp:use') || $accessToken->can('*')) {
                Log::debug('User authenticated via Sanctum token', [
                    'user_id' => $user->id,
                    'token_name' => $accessToken->name,
                ]);

                return $user;
            }
        }

        return null;
    }

    private function parseMCPTenantHeader(string $headerValue): object
    {
        // FORMAT host:tenantId:userId:token
        $parts = explode(':', $headerValue);
        $this->parsedHeader = (object)[
            'host' => $parts[0] ?? null,
            'tenantId' => $parts[1] ?? null,
            'userId' => $parts[2] ?? null,
            'token' => $parts[3] ?? null,
        ];
        return $this->parsedHeader;
    }
}
