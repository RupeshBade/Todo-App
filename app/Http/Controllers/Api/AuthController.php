<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password; // <-- CRITICAL: Import Laravel's rule helper

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'regex:/^[^0-9]/' ensures the string does NOT start with a number 0-9
            'name' => ['required', 'string', 'max:255', 'regex:/^[^0-9]/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                Password::min(8)          // Minimum 8 characters
                    ->letters()          // Requires at least one letter
                    ->mixedCase()        // Requires both uppercase and lowercase letters
                    ->numbers()          // Requires at least one number
                    ->symbols()          // Requires at least one special character (like !, @, #, $, etc.)
            ]
        ], [
            // Custom error messages to make it beginner-friendly for your users
            'name.regex' => 'The username must not start with a numeric character.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        // Upgraded login validation to use array format (best practice)
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
