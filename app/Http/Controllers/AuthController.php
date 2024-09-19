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
     * Handle the login request and generate a Sanctum token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
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
