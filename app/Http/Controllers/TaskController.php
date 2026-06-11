<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\MockApiCurl;

class TaskController extends Controller
{
    /**
     * Display dashboard with local tasks and raw remote MockAPI users stream.
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->latest()->get();

        // Pull active data collections directly from your specific MockAPI backend link
        $apiResponse = MockApiCurl::get('/users');
        $externalContacts = [];

        if (is_array($apiResponse) && !isset($apiResponse['error'])) {
            foreach ($apiResponse as $user) {
                if (!is_array($user)) {
                    continue;
                }

                // Normalizes keys to match MockAPI's schema variables
                $externalContacts[] = [
                    'id'          => isset($user['id']) ? (string)$user['id'] : '0',
                    'firstName'   => $user['name'] ?? 'Unnamed Profile',
                    'email'       => $user['Email'] ?? 'No Email Profile Linked',
                    'phoneNumber' => $user['phone'] ?? 'No Address Data'
                ];
            }
        }

        // Bundle data strings for token visualizer mapping
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

        return view('todo', compact('tasks', 'secureJwtToken', 'externalContacts'));
    }

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

    public function toggleStatus($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->update(['is_completed' => !$task->is_completed]);
        return redirect()->back();
    }

    public function edit($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $request->validate(['title' => 'required|max:255']);
        $task->update(['title' => $request->title]);
        return redirect()->route('tasks.index')->with('success', 'Task record updated.');
    }

    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();
        return redirect()->back()->with('success', 'Local task record completely removed.');
    }

    /**
     * Post a new user to MockAPI.
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
            'Email' => $request->input('email'),
            'phone' => $request->input('phone')
        ];

        MockApiCurl::post('/users', $payloadData);

        return redirect()->back()->with('success', 'User synced to MockAPI successfully!');
    }

    /**
     * Delete a user directly from your MockAPI endpoint list.
     */
    /**
     * Delete a user directly from your MockAPI endpoint list.
     */
    public function wipeContact($id)
    {
        if (!empty($id) && $id !== '0') {
            // This triggers the custom Curl service facade correctly
            \App\Facades\MockApiCurl::delete('/users/' . $id);
            return redirect()->back()->with('success', 'User completely wiped from MockAPI!');
        }
        return redirect()->back()->with('api_error', 'Invalid User ID context received.');
    }

    public function showRawJson()
    {
        $apiResponse = MockApiCurl::get('/users');
        return response()->json($apiResponse);
    }
}
