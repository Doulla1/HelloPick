<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle the login request and generate a Sanctum token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Retrieve validated credentials from the LoginRequest
        $credentials = $request->validated();

        // Attempt to authenticate the user with the provided credentials
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if the authenticated user is an admin
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        // Generate Sanctum token for the admin
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }
}
