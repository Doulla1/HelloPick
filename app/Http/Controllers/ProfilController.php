<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilRequest;
use App\Models\Profil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
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
}
