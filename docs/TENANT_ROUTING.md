# Multi-Environment Tenant Routing

## Problem Statement
This application needs to support different URL patterns for tenant routing across three environments:
- **Production (non-staging)**: `https://tenant.example.com` (subdomain-based)
- **Staging**: `https://example.com/tenant` (path prefix-based)
- **Local**: `http://localhost:8000` (no tenant parameter)

## Solution

### 1. Middleware: `SetTenantUrlDefaults`
Located at: `app/Http/Middleware/SetTenantUrlDefaults.php`

This middleware automatically sets default URL parameters based on the current environment and tenant context. It runs on every web request and calls `TenantUrl::setDefaultParameters()`.

**Key Behavior:**
- In **staging** (prefix-based routing): Sets `account` as a default URL parameter
- In **production subdomain** routing: Does nothing (subdomain is automatic)
- In **local** development: Does nothing (no account parameter needed)

### 2. Helper Class: `TenantUrl`
Located at: `app/Support/TenantUrl.php`

Provides methods to generate tenant-aware URLs correctly for each environment.

**Methods:**
- `TenantUrl::route(string $name, array $parameters = [], bool $absolute = true)`: Generate a route URL with proper tenant context
- `TenantUrl::setDefaultParameters()`: Set default route parameters (called by middleware)

### 3. Route Configuration
Located at: `routes/web.php`

```php
if (app()->isProduction()) {
    if(app()->environment('staging')) {
        // Staging: Path prefix routing
        Route::prefix('{account}')->group(function () {
            require __DIR__ . '/tenant.php';
        });
    } else {
        // Production: Subdomain routing
        Route::domain('{account}.' . $APP_HOST)->group(function () {
            require __DIR__ . '/tenant.php';
        });
    }
} else {
    // Local: No account parameter
    Route::prefix('')->group(function () {
        require __DIR__ . '/tenant.php';
    });
}
```

## Usage in Controllers

### ✅ Correct (Simple)
```php
// The middleware handles adding account parameter automatically
return to_route('dashboard');
return redirect()->route('dashboard');
```

### ❌ Incorrect (Don't do this)
```php
// Don't manually pass account parameter
$account = $request->route('account');
return to_route('dashboard', ['account' => $account]); // ❌ Wrong!
```

## How It Works

### Production (Subdomain Routing)
1. User visits `https://myspace.example.com/dashboard`
2. `Route::domain('{account}.')` captures `myspace` from subdomain
3. Tenant finder identifies tenant by `myspace`
4. Middleware does nothing (subdomain is already in URL)
5. `route('dashboard')` generates `https://myspace.example.com/dashboard`

### Staging (Path Prefix Routing)
1. User visits `https://example.com/myspace/dashboard`
2. `Route::prefix('{account}')` captures `myspace` from path
3. Tenant finder identifies tenant by `myspace`
4. Middleware sets `URL::defaults(['account' => 'myspace'])`
5. `route('dashboard')` generates `https://example.com/myspace/dashboard`

### Local Development
1. User visits `http://localhost:8000/dashboard`
2. No account parameter needed
3. Tenant finder uses default tenant or settings
4. Middleware does nothing
5. `route('dashboard')` generates `http://localhost:8000/dashboard`

## Advanced: Custom TenantUrl Helper

If you need more control in specific cases, you can use:

```php
use App\Support\TenantUrl;

// Generate tenant-aware route URL
$url = TenantUrl::route('dashboard');
$url = TenantUrl::route('projects.show', ['project' => $project->id]);
```

## Testing

The middleware ensures URLs are generated correctly across all environments without requiring manual parameter passing in controllers.

## Related Files
- `app/Http/Middleware/SetTenantUrlDefaults.php` - Middleware
- `app/Support/TenantUrl.php` - Helper class
- `bootstrap/app.php` - Middleware registration
- `routes/web.php` - Route configuration
- `app/Services/TenantFinder.php` - Tenant resolution logic
