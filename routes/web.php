<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Import BOTH packages here so Laravel knows where to find them
use Rupes\JetConverter\JetConverter;
use Intern\JwtConverter\JwtConverter;
use Illuminate\Http\Request;

// 1. Main To-Do Page (The List View)
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// 2. Handling Form Submissions (Create, Toggle, Update, Delete)
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

// 3. Dual Package Testing Route
Route::get('/test-package', function () {
    // Instantiate your older package
    $oldConverter = new JetConverter();

    // Instantiate your brand new package 
    $newConverter = new JwtConverter();

    return response()->json([
        'message' => 'Both custom packages are linked successfully!',
        'old_package_test' => $oldConverter->convert('laravel'),
        'new_package_test' => 'Class instantiated successfully! Ready to write lcobucci/jwt logic here.'
    ]);
});

Route::post('/api/sync-tasks', function (Request $request) {
    $jwtProcessor = new JwtConverter();

    // Grab the token sent in the request headers
    $token = $request->bearerToken();

    if (!$token) {
        return response()->json(['error' => 'No sync token provided.'], 400);
    }

    // Decode the token using your package's new method!
    $decodedData = $jwtProcessor->decode($token);

    return response()->json([
        'status' => 'Token Verified Successfully!',
        'message' => 'Your custom package parsed this data from the encrypted string.',
        'extracted_tasks' => $decodedData
    ]);
});

