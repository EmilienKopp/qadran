<?php

// use App\Http\Controllers\API\GitReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/git-report', [GitReportController::class, 'generate']);
});

Route::get('/artisan', [\App\Http\Controllers\Api\ArtisanController::class, 'run'])
    ->name('api.artisan')
    ->middleware(['auth:sanctum','api']);

// Route::post('/git-report', [GitReportController::class, 'generate']);

// Route all GET routes to just return the result of the DataAccess/Local class method called
Route::middleware('api')->group(function () {
    Route::get('/{model}/{method}', function (Request $request, $model, $method) {
        $dataAccessClass = 'App\\DataAccess\\Local\\' . ucfirst($model);
        $args = $request->all();
        if (class_exists($dataAccessClass) && method_exists($dataAccessClass, $method)) {
            return call_user_func([new $dataAccessClass, $method], ...$args);
        }
        return response()->json(['error' => 'Invalid data access or method'], 404);
    });
});