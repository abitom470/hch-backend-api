<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login and issue a Sanctum API token.
     */
    public function apiLogin(Request $request)
    {
        // 1. Validate credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Return a standard 401 Unauthorized JSON response
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials provided.'],
            ])->status(401);
        }

        // 3. Get the authenticated user model
        $user = Auth::user();

        // 4. Create and return the Sanctum API Token
        // 'authToken' is the name of the token granted, not the value.
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token, // <-- This 'token' field is what your React app expects!
        ]);
    }

    // You can add a logout route if needed, using the token's abilities:
    // public function logout(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();
    //     return response()->json(['message' => 'Logged out successfully']);
    // }
}