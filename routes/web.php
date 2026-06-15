<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('tasks.index');
    })->name('dashboard');

    // 1. Local App Database Tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // This line fixes the Route [tasks.toggle] not defined error:
    Route::patch('/tasks/{id}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');

    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // 2. MockAPI Stream Endpoint Synchronizations
    Route::post('/contacts', [TaskController::class, 'storeContact'])->name('contacts.store');

    // 3. Profile Routings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Wipe contact route (placed outside or inside auth depending on your preference)
Route::delete('/wipe-contact/{id}', [TaskController::class, 'wipeContact'])->name('wipeContact');


// Authentication routes
require __DIR__ . '/auth.php';
