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