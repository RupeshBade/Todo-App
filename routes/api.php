<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TaskController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/api-users', [TaskController::class, 'showRawJson'])->name('tasks.raw_json');
    Route::post('/api-users', [TaskController::class, 'storeRawJson'])->name('tasks.store_raw_json');
    Route::patch('/api-users', [TaskController::class, 'updateRawJson'])->name('tasks.update_raw_json');
    Route::delete('/api-users', [TaskController::class, 'destroyRawJson'])->name('tasks.destroy_raw_json');
});
