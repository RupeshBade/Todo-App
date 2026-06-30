<?php

namespace App\Http\Controllers\Api;

use App\Facades\MockApiCurl;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Facades\StarlinkCurl;

class ContactController extends Controller
{
    /**
     * 1. FETCH Data: GET /public/v2/contacts
     */
    public function index()
    {
        // Elegant relative call. Your service layer handles the base domain configuration.
        $response = MockApiCurl::get('public/v2/contacts');

        return response()->json([
            'status' => 'success',
            'action' => 'Fetch Starlink Contacts',
            'api_response' => $response
        ]);
    }

    /**
     * 2. STORE Data: POST /public/v2/contacts
     */
    public function store(Request $request)
    {
        $contactPayload = [
            'firstName'   => $request->input('first_name', 'Ram'),
            'lastName'    => $request->input('last_name', 'Nepali'),
            'email'       => $request->input('email', 'ram12@example.com'),
            'phoneNumber' => $request->input('phone', '+977 9812345678'),
        ];

        $response = MockApiCurl::post('public/v2/contacts', $contactPayload);

        return response()->json([
            'status' => 'success',
            'action' => 'Create Starlink Contact',
            'api_response' => $response
        ]);
    }

    /**
     * 3. DELETE Data: DELETE /public/v2/contacts/{subjectId}
     */
    public function destroy(int $subjectId)
    {
        $endpoint = 'public/v2/contacts/' . $subjectId;
        $response = MockApiCurl::delete($endpoint);

        return response()->json([
            'status' => 'success',
            'action' => "Delete MockAPI user ID: {$subjectId}",
            'api_response' => $response
        ]);
    }
}
