<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {


    Route::get('/dashboard', function () {
        return redirect()->route('tasks.index');
    })->middleware(['auth'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Show all tasks
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');

    // Create task
    Route::post('/tasks', [TaskController::class, 'store'])
        ->name('tasks.store');

    // Toggle task completion
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])
        ->name('tasks.toggle');

    // Show edit page
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])
        ->name('tasks.edit');

    // Update task
    Route::put('/tasks/{id}', [TaskController::class, 'update'])
        ->name('tasks.update');

    // Delete task
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->name('tasks.destroy');
});


require __DIR__ . '/auth.php';
