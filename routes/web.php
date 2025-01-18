<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClockEntryController;
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
    $user = User::find(Auth::id())->load('projects');
    return Inertia::render('Dashboard', compact('user'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'clock-entry'] , function(){
        Route::get('/', [ClockEntryController::class, 'index'])->name('clock-entry.index');
        Route::post('/store', [ClockEntryController::class, 'store'])->name('clock-entry.store');
        Route::get('/edit/{clock-entry}', [ClockEntryController::class, 'edit'])->name('clock-entry.edit');
        Route::patch('/update/{clock-entry}', [ClockEntryController::class, 'update'])->name('clock-entry.update');
        Route::delete('/destroy/{clock-entry}', [ClockEntryController::class, 'destroy'])->name('clock-entry.destroy');
    });
});

require __DIR__.'/auth.php';
