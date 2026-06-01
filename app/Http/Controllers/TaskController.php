<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Rupes\JetConverter\JetConverter;
use Intern\JwtConverter\JwtConverter; // Imported the new JWT Package

class TaskController extends Controller
{
    // READ: Display all tasks & generate secure JWT token
    public function index()
    {
        $tasks = Task::latest()->get();

        // Initialize the new JwtConverter package
        $jwtProcessor = new JwtConverter();

        // Prepare the payload array data from your database records
        $taskPayload = [];
        foreach ($tasks as $task) {
            $taskPayload[] = [
                'id' => $task->id,
                'title' => $task->title,
                'is_completed' => $task->is_completed
            ];
        }

        // Encode the payload into a cryptographically secure token
        // (Note: ensure your JwtConverter class has a matching method inside its src/JwtConverter.php)
        $secureJwtToken = '';
        try {
            // Adjust this function call name if you named it differently in your package file
            $secureJwtToken = $jwtProcessor->encode(['tasks' => $taskPayload]);
        } catch (\Exception $e) {
            $secureJwtToken = 'JWT token generation pending package logic: ' . $e->getMessage();
        }

        // Pass both tasks and the token down to your todo layout view
        return view('todo', compact('tasks', 'secureJwtToken'));
    }

    // CREATE: Store a new task using your JetConverter package
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        // Initialize your custom package logic
        $converter = new JetConverter();
        $processedTitle = $converter->convert($request->title);

        // Create the task with the transformed package output
        Task::create([
            'title' => $processedTitle,
            'is_completed' => false,
        ]);

        return redirect()->back()->with('success', 'Task created successfully with JetConverter!');
    }

    // UPDATE: Toggle task completion status
    public function toggleStatus(Task $task)
    {
        $task->update([
            'is_completed' => !$task->is_completed,
        ]);

        return redirect()->back()->with('success', 'Task status updated.');
    }

    // EDIT: Show the edit form for a specific task
    public function edit(int $id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    // UPDATE: Save the actual edited text title change
    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $task = Task::findOrFail($id);

        // Run the custom package conversion on updates too!
        $converter = new JetConverter();
        $processedTitle = $converter->convert($request->title);

        $task->update([
            'title' => $processedTitle,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    // DELETE: Delete a task
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
