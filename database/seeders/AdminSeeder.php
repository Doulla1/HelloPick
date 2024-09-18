<?php

namespace Database\Seeders;

use App\Models\Administrateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the admin already exists
        $adminEmail = env('ADMIN_EMAIL');

        if (Administrateur::where('email', $adminEmail)->doesntExist()) {
            // Create the admin with the data from the .env file
            Administrateur::create([
                'email' => $adminEmail,
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'type' => 'administrateur',
            ]);

            // Message to the console
            $this->command->info('Administrateur initial créé avec succès.');
        } else {
            $this->command->info('L\'administrateur avec cet email existe déjà.');
        }
    }
}
