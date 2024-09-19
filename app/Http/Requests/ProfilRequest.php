<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth ()->check (); // Ensure the user is authenticated
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB image
            'statut' => 'required|in:inactif,en attente,actif', // Ensure statut is valid
        ];
    }

    /*
    **
    * Get custom messages for validation errors.
    */
    public function messages(): array
    {
        return [
            'nom.required' => 'The nom field is required.',
            'prenom.required' => 'The prenom field is required.',
            'image.required' => 'An image is required.',
            'image.image' => 'The file must be a valid image.',
            'image.mimes' => 'Only jpeg, png, jpg, and gif files are allowed.',
            'statut.required' => 'The statut is required.',
            'statut.in' => 'The statut must be either inactif, en attente, or actif.',
        ];
    }
}
