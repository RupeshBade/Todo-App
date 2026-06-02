<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rupes\JetConverter\JetConverter;
use Intern\JwtConverter\JwtConverter;

class TaskController extends Controller
{
    // READ: Display user-specific tasks & generate secure JWT token
    public function index()
    {
        // Grab only the tasks belonging to the authenticated user
        $tasks = Task::where('user_id', Auth::id())
            ->latest()
            ->get();

        // Initialize the JwtConverter package
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
        $secureJwtToken = '';
        try {
            $secureJwtToken = $jwtProcessor->encode(['tasks' => $taskPayload]);
        } catch (\Exception $e) {
            $secureJwtToken = 'JWT token generation pending package logic: ' . $e->getMessage();
        }

        // Pass tasks and token to the todo view layout
        return view('todo', compact('tasks', 'secureJwtToken'));
    }

    // CREATE: Store a new task using your JetConverter package
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        // Initialize your custom package logic transformation
        $converter = new JetConverter();
        $processedTitle = $converter->convert($request->title);

        // Create the task with the transformed package output tied to the logged-in user
        Task::create([
            'title' => $processedTitle,
            'is_completed' => false,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Task created successfully with JetConverter!');
    }

    // UPDATE: Toggle task completion status
    public function toggleStatus(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->update([
            'is_completed' => !$task->is_completed,
        ]);

        return redirect()->back()->with('success', 'Task status updated.');
    }

    // EDIT: Show the edit form for a specific task
    public function edit(int $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    // UPDATE: Save edited text title updates
    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        // Run the custom package conversion on updates
        $converter = new JetConverter();
        $processedTitle = $converter->convert($request->title);

        $task->update([
            'title' => $processedTitle,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    // DELETE: Delete a task safely
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
