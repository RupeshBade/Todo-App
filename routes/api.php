<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TaskController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/api-users', [TaskController::class, 'showRawJson'])->name('tasks.raw_json');
Route::post('/api-users', [TaskController::class, 'storeRawJson'])->name('tasks.store_raw_json');
Route::patch('/api-users/{id}', [TaskController::class, 'updateRawJson'])->name('tasks.update_raw_json');
Route::delete('/api-users/{id}', [TaskController::class, 'destroyRawJson'])->name('tasks.destroy_raw_json');

Route::get('/api-Students', [TaskController::class, 'showStudentRawJson'])->name('students.raw_json');
Route::post('/api-Students', [TaskController::class, 'storeStudentRawJson'])->name('students.store_raw_json');
Route::patch('/api-Students/{id}', [TaskController::class, 'updateStudentRawJson'])->name('students.update_raw_json');
Route::delete('/api-Students/{id}', [TaskController::class, 'destroyStudentRawJson'])->name('students.destroy_raw_json');

Route::get('/jsonapi-posts', [TaskController::class, 'showPostRawJson'])->name('posts.raw_json');


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
