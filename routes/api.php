<?php

// use App\Http\Controllers\API\GitReportController;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/git-report', [GitReportController::class, 'generate']);
});

Route::get('/artisan', [\App\Http\Controllers\Api\ArtisanController::class, 'run'])
    ->name('api.artisan');
// Webhook endpoint for n8n to post Jira known issues (public, no auth)
Route::post('/webhooks/jira/known-issues', [\App\Http\Controllers\KnownIssueWebhookController::class, 'store'])
    ->name('api.webhooks.jira.known-issues');

Route::get('/instance-url/{host}', function ($host) {
    \Log::debug("Fetching instance URL for host: {$host}");

    return [
        'instance_url' => \App\Support\InstanceUrl::build($host),
    ];
})->name('api.instanceUrl');

// Repository Proxy - Single endpoint for all repository calls
Route::middleware('api')->post('/repository/call', [\App\Http\Controllers\Api\RepositoryProxyController::class, 'call']);

// Repository API Routes (for direct REST access)
Route::middleware('api')->group(function () {
    // User routes
    Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
    Route::get('/users/{id}', [\App\Http\Controllers\Api\UserController::class, 'show']);
    Route::get('/users/by-workos-id/{workosId}', [\App\Http\Controllers\Api\UserController::class, 'byWorkosId']);
    Route::get('/users/by-email', [\App\Http\Controllers\Api\UserController::class, 'byEmail']);
    Route::post('/users', [\App\Http\Controllers\Api\UserController::class, 'store']);
    Route::put('/users/{id}', [\App\Http\Controllers\Api\UserController::class, 'update']);
    Route::delete('/users/{id}', [\App\Http\Controllers\Api\UserController::class, 'destroy']);

    // Organization routes
    Route::get('/organizations', [\App\Http\Controllers\Api\OrganizationController::class, 'index']);
    Route::get('/organizations/{id}', [\App\Http\Controllers\Api\OrganizationController::class, 'show']);
    Route::get('/organizations/by-user/{userId}', [\App\Http\Controllers\Api\OrganizationController::class, 'byUser']);
    Route::post('/organizations', [\App\Http\Controllers\Api\OrganizationController::class, 'store']);
    Route::put('/organizations/{id}', [\App\Http\Controllers\Api\OrganizationController::class, 'update']);
    Route::delete('/organizations/{id}', [\App\Http\Controllers\Api\OrganizationController::class, 'destroy']);

    // Project routes
    Route::get('/projects', [\App\Http\Controllers\Api\ProjectController::class, 'index']);
    Route::get('/projects/{id}', [\App\Http\Controllers\Api\ProjectController::class, 'show']);
    Route::get('/projects/by-organization/{organizationId}', [\App\Http\Controllers\Api\ProjectController::class, 'byOrganization']);
    Route::post('/projects', [\App\Http\Controllers\Api\ProjectController::class, 'store']);
    Route::put('/projects/{id}', [\App\Http\Controllers\Api\ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [\App\Http\Controllers\Api\ProjectController::class, 'destroy']);

    // Report routes
    Route::get('/reports', [\App\Http\Controllers\Api\ReportController::class, 'index']);
    Route::get('/reports/{id}', [\App\Http\Controllers\Api\ReportController::class, 'show']);
    Route::get('/reports/by-project/{projectId}', [\App\Http\Controllers\Api\ReportController::class, 'byProject']);
    Route::post('/reports', [\App\Http\Controllers\Api\ReportController::class, 'store']);
    Route::put('/reports/{id}', [\App\Http\Controllers\Api\ReportController::class, 'update']);
    Route::delete('/reports/{id}', [\App\Http\Controllers\Api\ReportController::class, 'destroy']);

    // Tenant routes
    Route::get('/tenants', [\App\Http\Controllers\Api\TenantController::class, 'index']);
    Route::get('/tenants/{id}', [\App\Http\Controllers\Api\TenantController::class, 'show']);
    Route::get('/tenants/by-domain', [\App\Http\Controllers\Api\TenantController::class, 'byDomain']);
    Route::post('/tenants', [\App\Http\Controllers\Api\TenantController::class, 'store']);
    Route::put('/tenants/{id}', [\App\Http\Controllers\Api\TenantController::class, 'update']);
    Route::delete('/tenants/{id}', [\App\Http\Controllers\Api\TenantController::class, 'destroy']);

    // ClockEntry routes
    Route::get('/clock-entries', [\App\Http\Controllers\Api\ClockEntryController::class, 'index']);
    Route::get('/clock-entries/{id}', [\App\Http\Controllers\Api\ClockEntryController::class, 'show']);
    Route::get('/clock-entries/by-user/{userId}', [\App\Http\Controllers\Api\ClockEntryController::class, 'byUser']);
    Route::get('/clock-entries/active-by-user/{userId}', [\App\Http\Controllers\Api\ClockEntryController::class, 'activeByUser']);
    Route::post('/clock-entries', [\App\Http\Controllers\Api\ClockEntryController::class, 'store']);
    Route::put('/clock-entries/{id}', [\App\Http\Controllers\Api\ClockEntryController::class, 'update']);
    Route::delete('/clock-entries/{id}', [\App\Http\Controllers\Api\ClockEntryController::class, 'destroy']);

    // Task routes
    Route::get('/tasks', [\App\Http\Controllers\Api\TaskController::class, 'index']);
    Route::get('/tasks/{id}', [\App\Http\Controllers\Api\TaskController::class, 'show']);
    Route::get('/tasks/by-project/{projectId}', [\App\Http\Controllers\Api\TaskController::class, 'byProject']);
    Route::post('/tasks', [\App\Http\Controllers\Api\TaskController::class, 'store']);
    Route::put('/tasks/{id}', [\App\Http\Controllers\Api\TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [\App\Http\Controllers\Api\TaskController::class, 'destroy']);
});

// Route all GET routes to just return the result of the DataAccess/Local class method called
Route::middleware('api')->group(function () {
    // Special route for find method with ID parameter
    Route::get('/{model}/find/{id}', function (Request $request, $model, $id) {
        $dataAccessClass = 'App\\DataAccess\\Local\\'.ucfirst($model);

        \Log::debug('API GET Request (find)', [
            'dataAccessClass' => $dataAccessClass,
            'method' => 'find',
            'id' => $id,
        ]);

        if (class_exists($dataAccessClass) && method_exists($dataAccessClass, 'find')) {
            $result = (new $dataAccessClass)->find($id);
            \Log::debug('API GET Response', ['result' => $result]);

            return $result;
        }

        return response()->json(['error' => 'Invalid data access or method'], 404);
    });

    Route::get('/{model}/{method}', function (Request $request, $model, $method) {
        $dataAccessClass = 'App\\DataAccess\\Local\\'.ucfirst($model);
        $args = $request->query(); // Use query() instead of all() to exclude route params

        \Log::debug('API GET Request', [
            'dataAccessClass' => $dataAccessClass,
            'method' => $method,
            'args' => $args,
            'class_exists' => class_exists($dataAccessClass),
            'method_exists' => class_exists($dataAccessClass) ? method_exists($dataAccessClass, $method) : null,
        ]);

        if (class_exists($dataAccessClass) && method_exists($dataAccessClass, $method)) {
            // Convert associative array to positional arguments using array_values
            $result = call_user_func([new $dataAccessClass, $method], ...array_values($args));
            \Log::debug('API GET Response', ['result' => $result]);

            return $result;
        }

        return response()->json(['error' => 'Invalid data access or method'], 404);
    });
});

//
Route::prefix('n8n')->group(function () {
    // Decrypt tenant ID and lookup user from hashed token (used by n8n Server Auth node)
    // Note: This expects the HASHED token from the database, not the plain text Bearer token
    Route::post('/decrypt-tenant', function (Request $request) {
        $request->validate(['tenant_id' => 'required']);

        if ($request->header('X-N8N-Secret') !== config('services.n8n.secret')) {
            abort(403);
        }

        try {
            $tenant = \App\Models\Landlord\Tenant::where('hash', $request->input('tenant_id'))->firstOrFail();
            $tenant->makeCurrent();
            $tenantId = $tenant->id;

            // Token value here is the HASHED token from personal_access_tokens.token column
            $tokenValue = $request->input('token');
            \Log::debug('Fetching user ID for token', ['token' => substr($tokenValue, 0, 20).'...']);
            $userId = PersonalAccessToken::where('token', $tokenValue)->first()?->tokenable_id;

            return response()->json(['tenant_id' => $tenantId, 'user_id' => $userId]);
        } catch (DecryptException $e) {
            abort(400, 'Invalid encrypted value');
        }
    });
});
