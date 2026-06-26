<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GeniusSystem\CurlFacade\CurlFacade as MockApiCurl;

class TaskController extends Controller
{
    /**
     * Display dashboard with local tasks, raw remote MockAPI users stream, and JSONPlaceholder posts.
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->latest()->get();

        // 1. Fetch from First External Connection (MockAPI Users)
        $apiResponse = MockApiCurl::get('mock_api', '/users');
        $externalContacts = [];

        if (is_array($apiResponse) && !isset($apiResponse['error'])) {
            foreach ($apiResponse as $user) {
                $externalContacts[] = [
                    'id'    => $user['user_id'] ?? $user['id'] ?? '0',
                    'name'  => $user['name'] ?? 'Unnamed',
                    'Email' => $user['Email'] ?? 'No Email',
                    'phone' => $user['phone'] ?? 'No Phone'
                ];
            }
        }

        // 2. Fetch from Second External Connection (JSONPlaceholder)
        $jsonResponse = MockApiCurl::get('jsonplaceholder', '/posts');
        $publicPosts = [];

        if (is_array($jsonResponse) && !isset($jsonResponse['error'])) {
            $publicPosts = array_slice($jsonResponse, 0, 5); // Slice to grab just the first 5 posts
        }

        // 3. Fetch Student information streams from MockAPI Students
        $secondapiResponse = MockApiCurl::get('mock_api', '/Students');
        $externalStudents = [];

        if (is_array($secondapiResponse) && !isset($secondapiResponse['error'])) {
            foreach ($secondapiResponse as $student) {
                $externalStudents[] = [
                    'id'      => $student['user_id'] ?? $student['id'] ?? '0',
                    'name'    => $student['name'] ?? 'Unnamed',
                    'address' => $student['address'] ?? 'No Address mentioned',
                    'college' => $student['college'] ?? 'No college mentioned',
                    'level'   => $student['level'] ?? 'No level mentioned',
                ];
            }
        }

        // Prepared payload for local tasks view
        $taskPayload = [];
        foreach ($tasks as $task) {
            $taskPayload[] = [
                'id'           => $task->id,
                'title'        => $task->title,
                'is_completed' => (int)$task->is_completed
            ];
        }

        $combinedPayload = [
            'tasks'             => $taskPayload,
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

        return view('todo', compact('tasks', 'secureJwtToken', 'externalContacts', 'taskPayload', 'publicPosts', 'externalStudents'));
    }

    // --- Standard CRUD Methods ---
    public function store(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);
        Task::create([
            'user_id'      => Auth::id(),
            'title'        => $request->title,
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

    // --- External Sync Methods (Contacts) ---
    public function storeContact(Request $request)
    {
        $request->validate([
            'name'  => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        $payloadData = [
            'name'  => $request->input('name'),
            'Email' => $request->input('email'),
            'phone' => $request->input('phone')
        ];

        MockApiCurl::post('mock_api', '/users', $payloadData);

        return redirect()->back()->with('success', 'User synced to MockAPI successfully!');
    }

    public function wipeContact(int $id)
    {
        if (empty($id) || $id === '0') {
            return redirect()->back()->with('api_error', 'Invalid User ID context received.');
        }

        $response = MockApiCurl::delete('mock_api', '/users/' . $id);

        if (!isset($response['error'])) {
            return redirect()->back()->with('success', 'User completely wiped from MockAPI!');
        }

        return redirect()->back()->with('api_error', 'Failed to wipe: ' . ($response['message'] ?? 'Unknown error'));
    }

    // --- External Sync Methods (Students) ---
    public function storeStudent(Request $request)
    {
        $request->validate([
            'name'    => 'required|max:255',
            'address' => 'required|max:255',
            'college' => 'required',
            'level'   => 'required'
        ]);

        $payloadData = [
            'name'    => $request->input('name'),
            'address' => $request->input('address'),
            'college' => $request->input('college'),
            'level'   => $request->input('level')
        ];

        MockApiCurl::post('mock_api', '/Students', $payloadData);

        return redirect()->back()->with('success', 'Student synced to MockAPI Students successfully!');
    }

    public function wipeStudent(int $id)
    {
        if (empty($id) || $id === '0') {
            return redirect()->back()->with('api_error', 'Invalid Student ID context received.');
        }

        $response = MockApiCurl::delete('mock_api', '/Students/' . $id);

        if (!isset($response['error'])) {
            return redirect()->back()->with('success', 'Student completely removed from MockAPI Students!');
        }

        return redirect()->back()->with('api_error', 'Failed to remove: ' . ($response['message'] ?? 'Unknown error'));
    }

    // --- API JSON Methods (Postman Users Endpoints) ---
    public function showRawJson()
    {
        $apiResponse = MockApiCurl::get('mock_api', '/users');
        return response()->json($apiResponse);
    }

    public function storeRawJson(Request $request)
    {
        $apiResponse = MockApiCurl::post('mock_api', '/users', $request->all());
        return response()->json([
            'status'           => 'Successfully posted via Postman!',
            'mockapi_response' => $apiResponse
        ], 201);
    }

    public function updateRawJson(Request $request, int $id)
    {
        $response = MockApiCurl::patch('mock_api', '/users/' . $id, $request->except(['id']));

        return response()->json([
            'status'           => $response ? 'Success' : 'Failed',
            'mockapi_response' => $response
        ]);
    }

    public function destroyRawJson(int $id)
    {
        $response = MockApiCurl::delete('mock_api', '/users/' . $id);

        return response()->json([
            'status'       => $response ? 'Success' : 'Failed',
            'api_response' => $response
        ]);
    }

    // --- API JSON Methods (Postman Students Endpoints - Fixed Unique Method Names) ---
    public function showStudentRawJson()
    {
        $secondapiResponse = MockApiCurl::get('mock_api', '/Students');
        return response()->json($secondapiResponse);
    }

    public function storeStudentRawJson(Request $request)
    {
        $secondapiResponse = MockApiCurl::post('mock_api', '/Students', $request->all());
        return response()->json([
            'status'           => 'Successfully posted with Postman!',
            'mockapi_response' => $secondapiResponse
        ], 201);
    }

    public function updateStudentRawJson(Request $request, int $id)
    {
        $response = MockApiCurl::patch('mock_api', '/Students/' . $id, $request->except(['id']));

        return response()->json([
            'status'           => $response ? 'Success' : 'Failed',
            'mockapi_response' => $response
        ]);
    }

    public function destroyStudentRawJson(int $id)
    {
        $response = MockApiCurl::delete('mock_api', '/Students/' . $id);

        return response()->json([
            'status'       => $response ? 'Success' : 'Failed',
            'api_response' => $response
        ]);
    }

    public function showPostRawJson()
    {
        $jsonResponse = MockApiCurl::get('jsonplaceholder', '/posts');
        return response()->json($jsonResponse);
    }
}
