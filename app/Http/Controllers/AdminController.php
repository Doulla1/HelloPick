<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminCreateRequest;
use App\Models\Administrateur;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Handle the request to create a new administrator.
     *
     * Only admins are allowed to create new admins.
     *
     * @param AdminCreateRequest $request
     * @return JsonResponse
     */
    public function create(AdminCreateRequest $request): JsonResponse
    {
        // Retrieve validated data from the request
        $validated = $request->validated();

        try{
            // Create a new administrator
            $admin = Administrateur::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'message' => 'Administrator created successfully',
                'admin' => $admin,
            ], 201);
        } catch (\Exception $e) {
            // Log the error for further analysis
            Log::error('Failed to create administrator: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to create administrator',
            ], 500);
        }


    }
}
