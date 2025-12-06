# Multi-Environment Tenant Routing

## Problem Statement
This application needs to support different URL patterns for tenant routing across three environments:
- **Production (non-staging)**: `https://tenant.qadran.io` (subdomain-based)
- **Staging**: `https://qadran-stg-12345.cloud/tenant` (path prefix-based)
- **Local**: `http://localhost:8000` (no tenant parameter)

**Key Issue**: Auth routes (`login`, `register`, etc.) need to be within the tenant routing scope so they can redirect to tenant-specific routes like `dashboard`.

## Solution Overview

The routing works in two phases:
1. **Tenant Finding** (via subdomain parsing) - happens BEFORE route matching
2. **URL Generation** (via middleware) - happens AFTER tenant is found

## Request Lifecycle (VERIFIED IN PRODUCTION)

```
1. HTTP Request → https://tenant.qadran.io/login
                        ↓
2. TenantFinder runs (BEFORE route matching)
   - has_route: false ❌
   - route_params: "no route yet" ❌
   - Parses hostname: "tenant.qadran.io" → extracts "tenant"
   - Finds: Tenant where host = "tenant"
   - Sets: Tenant::current()
                        ↓
3. Route Matching
   - Matches: Route::domain('{account}.qadran.io')
   - Extracts: {account} = "tenant"
   - ✅ request()->route()->parameter('account') NOW works
                        ↓
4. AddHostToContext Middleware (could probable be removed)
   - Reads: Tenant::current()->host
   - Sets: Context::add('tenant_subdomain', 'tenant')
                        ↓
5. Route-Specific Middleware (guest, auth, etc.)
   - route('dashboard') → uses URL::defaults(), to avoid annoying "Missing required parameter" errors
                        ↓
6. Controller Execution
```

**Critical Discovery**: 

`Route::domain('{account}.')` parameter is NOT available during tenant finding. 
TenantFinder must parse the subdomain directly from the hostname.

## Implementation Details

### 1. TenantFinder (Subdomain Parsing)
Located at: `app/Services/TenantFinder.php`

```php
private function extractAccountFromHost(string $host): ?string
{
    // Route params NOT available yet - must parse manually!
    return UrlTools::getSubdomain($host);
}
```

### 2. Route Configuration
Located at: `routes/web.php`

Auth routes MUST be inside tenant route groups:

```php
if (app()->isProduction()) {
    Route::domain('{account}.' . $APP_HOST)->group(function () {
        require __DIR__ . '/tenant.php';
        require __DIR__ . '/auth.php';  // ← MUST be inside!
    });
}
```

**Why `Route::domain()` is still useful:**
- Provides `{account}` parameter for URL generation (after route matching)
- Makes routes explicitly tenant-scoped (documentation)
- Works with `URL::defaults()` in middleware

### 3. AddHostToContext Middleware
Located at: `app/Http/Middleware/AddHostToContext.php`

Adds host to context for reference.

### 4. RedirectIfAuthenticated Middleware
Located at: `app/Http/Middleware/RedirectIfAuthenticated.php`

Explicitly passes account parameter for redirects:

```php
$tenant = Tenant::current();
$params = [];
if ($tenant && (app()->environment('staging') || app()->isProduction())) {
    $params['account'] = $tenant->host;
}
return redirect()->route('dashboard', $params);
```

## Usage in Controllers

### ✅ Recommended (Simple)
```php
// Middleware handles account parameter automatically
return to_route('dashboard');
return redirect()->route('dashboard');
```

### ✅ Explicit (when needed)
```php
$tenant = Tenant::current();
$params = $tenant ? ['account' => $tenant->host] : [];
return redirect()->route('dashboard', $params);
```

## Accessing the Account Parameter

**Important**: The account parameter is only available AFTER route matching!

### Method 1: Via Current Tenant (Always Available)
```php
use App\Models\Landlord\Tenant;

$tenant = Tenant::current();
$account = $tenant?->host;  // ✅ Available from TenantFinder onwards
```

### Method 2: Via Route Parameter (After Route Matching Only)
```php
// ⚠️ Only works AFTER route matching!
$account = request()->route()?->parameter('account');
```

### Method 3: RequestContextResolver Helper
```php
use App\Support\RequestContextResolver;

// Tries route param first, falls back to null
$account = RequestContextResolver::getAccountParameter();
```

## Key Insights

1. **TenantFinder runs BEFORE route matching** - must parse subdomain manually
2. **Route::domain('{account}.')** - useful for URL generation, NOT for tenant finding  
3. **URL::defaults()** - makes route generation work across all environments
4. **Auth routes** - must be inside tenant route groups for proper scoping

## Testing

After these changes:
- ✅ Auth routes work in all environments
- ✅ Redirects to dashboard work correctly
- ✅ No "Missing required parameter for [Route: dashboard]" errors
- ✅ Tenant finding happens via subdomain parsing (verified in production logs)

## Related Files
- `app/Services/TenantFinder.php` - Subdomain parsing & tenant finding
- `app/Http/Middleware/AddHostToContext.php` - Middleware to add host to context
- `app/Http/Middleware/RedirectIfAuthenticated.php` - Guest middleware
- `app/Support/TenantUrl.php` - Helper class
- `app/Support/RequestContextResolver.php` - Context resolver with `getAccountParameter()`
- `bootstrap/app.php` - Middleware registration (prepend)
- `routes/web.php` - Route configuration (auth routes inside tenant groups)
- `routes/auth.php` - Auth routes

- `app/Support/RequestContextResolver.php` - Context resolver with `getAccountParameter()`
- `bootstrap/app.php` - Middleware registration (prepend)
- `routes/web.php` - Route configuration (**auth routes inside tenant groups**)
- `routes/auth.php` - Auth routes
- `app/Services/TenantFinder.php` - Tenant resolution logic

