<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Facades\MockApiCurl;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display dashboard with local tasks and raw remote MockAPI users stream.
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->latest()->get();

        $apiResponse = MockApiCurl::get('/users');
        $externalContacts = [];

        // In TaskController.php, update the mapping inside the index() method:
        if (is_array($apiResponse) && !isset($apiResponse['error'])) {
            foreach ($apiResponse as $user) {
                $externalContacts[] = [
                    // Map the 'user_id' from your API to 'id' so the Blade file recognizes it
                    'id'    => $user['user_id'] ?? $user['id'] ?? '0',
                    'name'  => $user['name'] ?? 'Unnamed',
                    'Email' => $user['Email'] ?? 'No Email',
                    'phone' => $user['phone'] ?? 'No Phone'
                ];
            }
        }

        // Prepared for view
        $taskPayload = [];
        foreach ($tasks as $task) {
            $taskPayload[] = [
                'id' => $task->id,
                'title' => $task->title,
                'is_completed' => (int)$task->is_completed
            ];
        }

        $combinedPayload = [
            'tasks' => $taskPayload,
            'starlink_contacts' => $externalContacts
        ];

        try {
            $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
            $payload = base64_encode(json_encode($combinedPayload));
            $signature = base64_encode(hash_hmac('sha256', "$header.$payload", 'secret', true));
            $secureJwtToken = "$header.$payload.$signature";
        } catch (\Exception $e) {
            $secureJwtToken = 'Token Generation Error: ' . $e->getMessage();
        }
        // Temporary debug: check exactly what keys the API provides
        // dd($externalContacts);

        return view('todo', compact('tasks', 'secureJwtToken', 'externalContacts', 'taskPayload'));
    }

    // --- Standard CRUD Methods ---
    public function store(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);
        Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'is_completed' => false,
        ]);
        return redirect()->back()->with('success', 'Local database task entry recorded.');
    }

    public function toggleStatus(int $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->update(['is_completed' => !$task->is_completed]);
        return redirect()->back();
    }

    public function edit(int $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, int $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $request->validate(['title' => 'required|max:255']);
        $task->update(['title' => $request->title]);
        return redirect()->route('tasks.index')->with('success', 'Task record updated.');
    }

    public function destroy(int $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();
        return redirect()->back()->with('success', 'Local task record completely removed.');
    }

    // --- External Sync Methods ---
    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        $payloadData = [
            'name'  => $request->input('name'),
            'Email' => $request->input('email'),
            'phone' => $request->input('phone')
        ];

        MockApiCurl::post('/users', $payloadData);

        return redirect()->back()->with('success', 'User synced to MockAPI successfully!');
    }

    public function wipeContact(int $id)
    {
        if (empty($id) || $id === '0') {
            return redirect()->back()->with('api_error', 'Invalid User ID context received.');
        }

        // Updated to use the Facade as requested
        $response = MockApiCurl::delete('/users/' . $id);

        if (!isset($response['error'])) {
            return redirect()->back()->with('success', 'User completely wiped from MockAPI!');
        }

        return redirect()->back()->with('api_error', 'Failed to wipe: ' . ($response['message'] ?? 'Unknown error'));
    }

    // --- API JSON Methods (Postman Endpoints) ---
    public function showRawJson()
    {
        $apiResponse = MockApiCurl::get('/users');
        return response()->json($apiResponse);
    }

    public function storeRawJson(Request $request)
    {
        $apiResponse = MockApiCurl::post('/users', $request->all());
        return response()->json([
            'status' => 'Successfully posted via Postman!',
            'mockapi_response' => $apiResponse
        ], 201);
    }

    public function updateRawJson(Request $request, int $id)
    {
        $mockApiUrl = 'https://6a2912e8f59cb8f65f1c674f.mockapi.io/api/v1/users/' . $id;
        $response = Http::patch($mockApiUrl, $request->except(['id'])); // Ensure this uses patch()
        return response()->json(
            [
                'status' => $response->successful() ? 'Success' : 'Failed',
                'mockapi_response' => $response->json()
            ],
            $response->status()
        );
    }

    public function destroyRawJson(int $id)
    {
        // The ID in the URL parameter is what you are passing from Postman
        $mockApiUrl = 'https://6a2912e8f59cb8f65f1c674f.mockapi.io/api/v1/users/' . $id;

        $response = \Illuminate\Support\Facades\Http::delete($mockApiUrl);

        return response()->json([
            'status' => $response->successful() ? 'Success' : 'Failed',
            'url_attempted' => $mockApiUrl, // Add this to see what URL is being called
            'api_response' => $response->json(),
            'status_code' => $response->status()
        ], $response->status());
    }
}
