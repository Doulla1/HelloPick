<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilRequest;
use App\Models\Profil;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
