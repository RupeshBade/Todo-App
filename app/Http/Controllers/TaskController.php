<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rupes\JetConverter\JetConverter;
use Intern\JwtConverter\JwtConverter;
use App\Facades\JsonPlaceholderCurl;

class TaskController extends Controller
{
    /**
     * FETCH (GET): Grab live profiles from JSONPlaceholder via audited cURL engine.
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->latest()->get();

        // Fire cURL custom engine pipeline via Facade
        $apiResponse = JsonPlaceholderCurl::get('/users');
        $externalContacts = [];

        if (is_array($apiResponse) && isset($apiResponse['error'])) {
            // Flash a non-blocking error status message directly to your dashboard interface
            session()->now('api_error', 'External API Gateway Alert: ' . $apiResponse['message']);
        } elseif (is_array($apiResponse)) {
            foreach ($apiResponse as $user) {
                $externalContacts[] = [
                    'subjectId'   => $user['id'] ?? '',
                    'firstName'   => $user['name'] ?? '',
                    'lastName'    => '',
                    'email'       => $user['email'] ?? '',
                    'phoneNumber' => $user['phone'] ?? ''
                ];
            }
        }

        // Handle JWT Cryptographic Synchronizer processing
        $jwtProcessor = new JwtConverter();
        $taskPayload = [];
        foreach ($tasks as $task) {
            $taskPayload[] = [
                'id' => $task->id,
                'title' => $task->title,
                'is_completed' => $task->is_completed
            ];
        }

        $combinedPayload = [
            'tasks' => $taskPayload,
            'external_contacts' => $externalContacts
        ];

        try {
            $secureJwtToken = $jwtProcessor->encode($combinedPayload);
        } catch (\Exception $e) {
            $secureJwtToken = 'JWT Error: ' . $e->getMessage();
        }

        return view('todo', compact('tasks', 'secureJwtToken', 'externalContacts'));
    }

    /**
     * STORE (POST): Send payload dataset to JSONPlaceholder via cURL facade.
     */
    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        $payloadData = [
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone')
        ];

        $response = JsonPlaceholderCurl::post('/users', $payloadData);

        // Trap errors during post request creation
        if (is_array($response) && isset($response['error'])) {
            return redirect()->back()->with('api_error', 'Failed to store contact: ' . $response['message']);
        }

        return redirect()->back()->with('success', 'Contact created on JSONPlaceholder via cURL! Returned ID: ' . ($response['id'] ?? '11'));
    }

    /**
     * DELETE (DELETE): Terminate resource endpoint path via cURL facade.
     */
    public function deleteContact($id)
    {
        $response = JsonPlaceholderCurl::delete('/users/' . $id);

        if (is_array($response) && isset($response['error'])) {
            return redirect()->back()->with('api_error', 'Failed to remove contact: ' . $response['message']);
        }

        return redirect()->back()->with('success', 'Contact ID #' . $id . ' successfully wiped via JSONPlaceholder API stream!');
    }

    public function showRawJson()
    {
        $apiResponse = JsonPlaceholderCurl::get('/users');

        if (is_array($apiResponse) && isset($apiResponse['error'])) {
            return response()->json([
                'status' => 'error',
                'message' => $apiResponse['message']
            ], 500);
        }

        return response()->json($apiResponse);
    }

    public function store(Request $request, JetConverter $converter)
    {
        $request->validate(['title' => 'required|max:255']);

        Task::create([
            'title' => $converter->convert($request->title),
            'is_completed' => false,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Task created successfully with JetConverter!');
    }

    public function toggleStatus(Task $task)
    {
        if ($task->user_id !== Auth::id()) abort(403);
        $task->update(['is_completed' => !$task->is_completed]);
        return redirect()->back()->with('success', 'Task status updated.');
    }

    public function edit(int $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, int $id, JetConverter $converter)
    {
        $request->validate(['title' => 'required|max:255']);
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $task->update(['title' => $converter->convert($request->title)]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) abort(403);
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
