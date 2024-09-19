<?php

namespace Database\Factories;

use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profil>
 */
class ProfilFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profil::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // path to the storage folder where the images will be stored
        $imagePath = storage_path('app/public/images');

        // Generate the images folder if it doesn't exist
        if (!Storage::exists('public/images')) {
            Storage::makeDirectory('public/images');
        }
        // Generate a random image using faker
        $image = $this->faker->image($imagePath, 400, 300, 'people', false);

        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'image' => 'images/' . $image,
            'statut' => $this->faker->randomElement(['inactif', 'en attente', 'actif']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
