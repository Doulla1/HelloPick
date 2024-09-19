<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilRequest;
use App\Models\Profil;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    protected TokenService $tokenService;

    /**
     * Create a new controller instance.
     *
     * Inject the TokenService into the controller via dependency injection
     *
     * @param TokenService $tokenService
     */
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Handle the request to create a new profil.
     *
     * @param ProfilRequest $request
     * @return JsonResponse
     */
    public function store(ProfilRequest $request): JsonResponse
    {
        // Retrieve validated data from the request
        $validated = $request->validated();

        // Store the image securely in the storage/public/images folder
        $path = $request->file('image')->store('images', 'public');

        // Create a new profil
        $profil = Profil::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'image' => $path,
            'statut' => $validated['statut'],
        ]);

        return response()->json([
            'message' => 'Profil created successfully',
            'profil' => $profil,
        ], 201);
    }

    /**
     * Return all active profiles without the 'statut' field for non-admins but with the 'statut' field for admins.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getActiveProfiles(Request $request): JsonResponse
    {
        // Extract the token from the request header
        $token = $this->tokenService->extractToken($request->header('Authorization'));

        $user = null;

        if ($token) {
            // Validate the token and retrieve the associated user
            $user = $this->tokenService->validateToken($token);
        }

        if ($user && $user->isAdmin()) {
            // Admins can see the 'statut' field
            $profils = Profil::where('statut', 'actif')->get([
                'id', 'nom', 'prenom', 'image', 'statut', 'created_at', 'updated_at'
            ]);
        } else {
            // Non-admins or anonymous users cannot see 'statut'
            $profils = Profil::where('statut', 'actif')->get([
                'id', 'nom', 'prenom', 'image', 'created_at', 'updated_at'
            ]);
        }

        return response()->json([
            'profils' => $profils,
        ], 200);
    }

    /**
     * Update an existing profile.
     *
     * @param ProfilRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ProfilRequest $request, int $id): JsonResponse
    {
        // Retrieve validated data from the request
        $validated = $request->validated();

        $profil = Profil::findOrFail($id);


        // Check if a new image is uploaded and store it
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($profil->image) {
                Storage::disk('public')->delete($profil->image);
            }

            // Store the new image
            $path = $request->file('image')->store('images', 'public');
            $profil->image = $path;
        }

        // Update the other fields
        $profil->nom = $validated['nom'];
        $profil->prenom = $validated['prenom'];
        $profil->statut = $validated['statut'];
        $profil->save();

        return response()->json([
            'message' => 'Profil updated successfully',
            'profil' => $profil,
        ], 200);
    }

    /**
     * Delete an existing profile.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $profil = Profil::findOrFail($id);

        // Delete the profile image if it exists
        if ($profil->image) {
            Storage::disk('public')->delete($profil->image);
        }

        // Delete the profile
        $profil->delete();

        return response()->json([
            'message' => 'Profil deleted successfully',
        ], 200);
    }
}
