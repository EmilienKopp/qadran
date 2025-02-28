<?php

use App\Http\Controllers\API\GitReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/git-report', [GitReportController::class, 'generate']);
});

Route::post('/git-report', [GitReportController::class, 'generate']);