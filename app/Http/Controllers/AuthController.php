<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Login an admin and generate a Sanctum token.
     *
     * This endpoint allows an admin to log in and obtain a token for authentication.
     *
     * @group Authentication
     *
     * @bodyParam email string required The email of the admin. Example: admin@example.com
     * @bodyParam password string required The password of the admin. Example: password123
     *
     * @response 200 {
     *  "message": "Login successful",
     *  "token": "3|pSswzSRJfmrJELKJJZfe6xzPM77c7XMQQQq3nsdj"
     * }
     * @response 401 {
     *  "message": "Invalid credentials"
     * }
     * @response 403 {
     *  "message": "Unauthorized access"
     * }
     * @response 500 {
     *  "message": "Login failed. Please try again later."
     * }
     */
    public function login(LoginRequest $request)
    {
        // Utilisation d'un try-catch pour capturer les erreurs potentielles
        try {
            // Retrieve validated credentials from the LoginRequest
            $credentials = $request->validated ();

            // Attempt to authenticate the user with the provided credentials
            if (!Auth::attempt ($credentials)) {
                return response ()->json (['message' => 'Invalid credentials'], 401);
            }

            // Check if the authenticated user is an admin
            $user = Auth::user ();
            if (!$user->isAdmin ()) {
                return response ()->json (['message' => 'Unauthorized access'], 403);
            }

            // Generate Sanctum token for the admin
            $token = $user->createToken ('auth_token')->plainTextToken;

            return response ()->json ([
                'message' => 'Login successful',
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            // log the error for further analysis
            Log::error ('Login failed: ' . $e->getMessage ());

            // Return a generic error message
            return response ()->json ([
                'message' => 'Login failed. Please try again later.',
            ], 500);
        }
    }
}
