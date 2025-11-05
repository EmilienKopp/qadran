# Multi-Environment Tenant Routing

## Problem Statement
This application needs to support different URL patterns for tenant routing across three environments:
- **Production (non-staging)**: `https://tenant.example.com` (subdomain-based)
- **Staging**: `https://example.com/tenant` (path prefix-based)
- **Local**: `http://localhost:8000` (no tenant parameter)

**Key Issue**: Auth routes (`login`, `register`, etc.) need to be within the tenant routing scope so they can redirect to tenant-specific routes like `dashboard`.

## Solution

### 1. Middleware: `SetTenantUrlDefaults` (runs early)
Located at: `app/Http/Middleware/SetTenantUrlDefaults.php`

This middleware automatically sets default URL parameters based on the current environment and tenant context. It's **prepended** to the web middleware stack to run early, before any route-specific middleware.

**Key Behavior:**
- In **staging** (prefix-based routing): Sets `account` as a default URL parameter
- In **production** (subdomain routing): Sets `account` as a default domain parameter
- In **local** development: Does nothing (no account parameter needed)

### 2. Auth Routes Scoping
Located at: `routes/web.php`

Auth routes are now **included inside** the tenant route groups so they inherit the proper routing context:

```php
if (app()->isProduction()) {
    if(app()->environment('staging')) {
        Route::prefix('{account}')->group(function () {
            require __DIR__ . '/tenant.php';
            require __DIR__ . '/auth.php';  // ← Inside the group
        });
    } else {
        Route::domain('{account}.' . $APP_HOST)->group(function () {
            require __DIR__ . '/tenant.php';
            require __DIR__ . '/auth.php';  // ← Inside the group
        });
    }
} else {
    Route::prefix('')->group(function () {
        require __DIR__ . '/tenant.php';
        require __DIR__ . '/auth.php';      // ← Inside the group
    });
}
```

### 3. RedirectIfAuthenticated Middleware
Located at: `app/Http/Middleware/RedirectIfAuthenticated.php`

This middleware explicitly passes the account parameter when redirecting to the dashboard:

```php
$tenant = Tenant::current();

// Build parameters based on environment and tenant
$params = [];
if ($tenant && (app()->environment('staging') || app()->isProduction())) {
    $params['account'] = $tenant->host;
}

return redirect()->route('dashboard', $params);
```

### 4. Helper Class: `TenantUrl`
Located at: `app/Support/TenantUrl.php`

Provides methods to generate tenant-aware URLs correctly for each environment (optional use).

## Usage in Controllers

### ✅ Correct (Simple - Recommended)
```php
// The middleware handles adding account parameter automatically
return to_route('dashboard');
return redirect()->route('dashboard');
```

### ✅ Also Correct (Explicit - for guest middleware)
```php
// Manually pass account parameter when needed
$tenant = Tenant::current();
$params = $tenant ? ['account' => $tenant->host] : [];
return redirect()->route('dashboard', $params);
```

## How It Works

### Request Lifecycle Order

It might seem **circular** that:
- TenantFinder uses the hostname to find the tenant
- SetTenantUrlDefaults uses `Tenant::current()` to set URL defaults

**Important Discovery:** TenantFinder runs BEFORE route matching, so it CANNOT rely on route parameters. It must parse the hostname directly.

#### Step-by-Step Execution:

1. **HTTP Request Received**
   - Request: `https://myspace.qadran.io/login`
   - Host: `myspace.qadran.io`

2. **TenantFinder Runs** (Spatie Multitenancy, runs BEFORE route matching)
   - Parses hostname: `myspace.qadran.io` → extracts subdomain `myspace`
   - Finds the Tenant model based on `host = "myspace"`
   - Sets `Tenant::current()` globally
   - ⚠️ **Route parameters are NOT available yet!**

3. **Route Matching**
   - Laravel matches route: `Route::domain('{account}.qadran.io')`
   - Extracts parameter: `{account} = "myspace"`
   - ✅ Now `request()->route()->parameter('account')` works!

4. **SetTenantUrlDefaults Middleware** (PREPENDED to web stack)
   - Reads `Tenant::current()->host` 
   - Sets `URL::defaults(['account' => 'myspace'])`

5. **Route-Specific Middleware** (guest, auth, etc.)
   - Generates URLs like `route('dashboard')`
   - Uses `URL::defaults()` to fill in the `{account}` parameter

6. **Controller/Route Execution**

**Key Insight:** TenantFinder extracts the account from the **hostname directly** (by parsing the subdomain), not from route parameters, because it runs before route matching.


**Key Insight:** The `{account}` parameter is extracted during route matching, which happens BEFORE the TenantFinder or any middleware runs. So TenantFinder reads an already-available value, not something it needs to create.

### Production (Subdomain Routing)
1. User visits `https://myspace.example.com/login`
2. `Route::domain('{account}.')` captures `myspace` from subdomain
3. Tenant finder identifies tenant by `myspace`
4. Middleware sets `URL::defaults(['account' => 'myspace'])`
5. `route('dashboard')` generates `https://myspace.example.com/dashboard`
6. Auth routes redirect to `https://myspace.example.com/dashboard`

### Staging (Path Prefix Routing)
1. User visits `https://example.com/myspace/login`
2. `Route::prefix('{account}')` captures `myspace` from path
3. Tenant finder identifies tenant by `myspace`
4. Middleware sets `URL::defaults(['account' => 'myspace'])`
5. `route('dashboard')` generates `https://example.com/myspace/dashboard`
6. Auth routes redirect to `https://example.com/myspace/dashboard`

### Local Development
1. User visits `http://localhost:8000/login`
2. No account parameter needed
3. Tenant finder uses default tenant
4. Middleware does nothing
5. `route('dashboard')` generates `http://localhost:8000/dashboard`
6. Auth routes redirect to `http://localhost:8000/dashboard`

## Key Changes Made

1. ✅ **Moved `SetTenantUrlDefaults` to prepend** instead of append (runs first)
2. ✅ **Moved `auth.php` inside tenant route groups** (critical fix)
3. ✅ **Updated `RedirectIfAuthenticated`** to explicitly pass account parameter
4. ✅ **Updated `TenantUrl::setDefaultParameters()`** to handle both staging and production

## Testing

After these changes:
- ✅ Auth routes work in all environments
- ✅ Redirects to dashboard work correctly
- ✅ No "Missing required parameter for [Route: dashboard]" errors

## Accessing the Account Parameter

You can access the `{account}` route parameter (from subdomain or path prefix) from anywhere in your application:

### Method 1: RequestContextResolver (Recommended)
```php
use App\Support\RequestContextResolver;

$account = RequestContextResolver::getAccountParameter();
```

### Method 2: Request Helper
```php
$account = request()->route()?->parameter('account');
```

### Method 3: Via Current Tenant
```php
use App\Models\Landlord\Tenant;

$tenant = Tenant::current();
$account = $tenant?->host;
```

### Method 4: Global Helper
```php
$account = account();
```

## Related Files
- `app/Http/Middleware/SetTenantUrlDefaults.php` - Early middleware
- `app/Http/Middleware/RedirectIfAuthenticated.php` - Guest middleware
- `app/Support/TenantUrl.php` - Helper class
- `app/Support/RequestContextResolver.php` - Context resolver with `getAccountParameter()`
- `bootstrap/app.php` - Middleware registration (prepend)
- `routes/web.php` - Route configuration (**auth routes inside tenant groups**)
- `routes/auth.php` - Auth routes
- `app/Services/TenantFinder.php` - Tenant resolution logic

