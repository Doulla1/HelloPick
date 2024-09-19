<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminCreateRequest;
use App\Models\Administrateur;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

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

        // Create a new administrator
        $admin = Administrateur::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Administrator created successfully',
            'admin' => $admin,
        ], 201);
    }
}
