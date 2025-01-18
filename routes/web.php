<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClockEntryController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = User::find(Auth::id())->load(['projects']);
    $clockEntries = ClockEntry::where('user_id', Auth::id())->get();
    return Inertia::render('Dashboard', compact('user'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'clock-entry'] , function(){
        Route::post('/store', [ClockEntryController::class, 'store'])->name('clock-entry.store');
    });

    Route::group(['prefix' => 'project'] , function(){
        Route::get('/', [ProjectController::class, 'index'])->name('project.index');
        Route::get('/create', [ProjectController::class, 'create'])->name('project.create');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('project.show');
        Route::post('/store', [ProjectController::class, 'store'])->name('project.store');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('project.edit');
        Route::patch('/{project}', [ProjectController::class, 'update'])->name('project.update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('project.destroy');
    });


});

require __DIR__.'/auth.php';
