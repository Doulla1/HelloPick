<?php

namespace Tests\Feature;

use App\Models\Profil;
use App\Models\User;
use App\Services\TokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfilControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $tokenService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the TokenService
        $this->tokenService = $this->createMock(TokenService::class);
        $this->app->instance(TokenService::class, $this->tokenService);
    }

    #[Test]
    public function it_creates_a_profil_successfully()
    {
        // Simulate file upload
        Storage::fake('public');
        $file = UploadedFile::fake()->image('profile.jpg');

        // Create a user and authenticate them
        $admin = User::factory()->create([
            'type' => 'administrateur',]);
        $this->actingAs($admin, 'sanctum'); // Authenticate the user via Sanctum

        // Send a request to create a profile
        $response = $this->postJson('/api/profils', [
            'nom' => 'John',
            'prenom' => 'Doe',
            'statut' => 'actif',
            'image' => $file,
        ]);

        // Assert the profile was created successfully
        $response->assertStatus(201, "Expected status code 201 but got {$response->getStatusCode()}.");
        $this->assertDatabaseHas('profils', [
            'nom' => 'John',
            'prenom' => 'Doe',
            'statut' => 'actif',
        ]);

        // Check if the image was stored
        Storage::disk('public')->assertExists('images/' . $file->hashName());
    }

    #[Test]
    public function it_returns_active_profiles_for_non_admins_without_statut()
    {
        // Create a profile with 'actif' statut
        Profil::factory()->create([
            'nom' => 'John',
            'prenom' => 'Doe',
            'statut' => 'actif',
        ]);

        // Simulate non-admin user
        $this->tokenService->method('validateToken')->willReturn(null); // Anonymous user

        // Send a request to get active profiles
        $response = $this->getJson('/api/profils-actifs');

        // Assert response does not contain the 'statut' field
        $response->assertStatus(200, "Expected status code 200 but got {$response->getStatusCode()}.");
        $response->assertJsonMissing(['statut']);
        $response->assertJsonFragment([
            'nom' => 'John',
            'prenom' => 'Doe',
        ]);
    }

    #[Test]
    public function it_updates_a_profile_successfully()
    {
        // Create an admin user
        $admin = User::factory()->create([
            'type' => 'administrateur',
        ]);
        $this->actingAs($admin, 'sanctum'); // Authenticate the user via Sanctum

        // Simulate file upload
        Storage::fake('public');
        $file = UploadedFile::fake()->image('new_profile.jpg');

        // Create a profile
        $profil = Profil::factory()->create();

        // Send a request to update the profile
        $response = $this->putJson("/api/profils/{$profil->id}", [
            'nom' => 'Jane',
            'prenom' => 'Smith',
            'statut' => 'en attente',
            'image' => $file,
        ]);

        // Assert the profile was updated successfully
        $response->assertStatus(200, "Expected status code 200 but got {$response->getStatusCode()}.");
        $this->assertDatabaseHas('profils', [
            'id' => $profil->id,
            'nom' => 'Jane',
            'prenom' => 'Smith',
            'statut' => 'en attente',
        ]);

        // Check if the new image was stored
        Storage::disk('public')->assertExists('images/' . $file->hashName());
    }

    #[Test]
    public function it_deletes_a_profile_successfully()
    {
        // Create an admin user
        $admin = User::factory()->create([
            'type' => 'administrateur',
        ]);
        $this->actingAs($admin, 'sanctum'); // Authenticate the user via Sanctum

        // Create a profile
        $profil = Profil::factory()->create();

        // Send a request to delete the profile
        $response = $this->deleteJson("/api/profils/{$profil->id}");

        // Assert the profile was deleted successfully
        $response->assertStatus(200, "Expected status code 200 but got {$response->getStatusCode()}.");
        $this->assertDatabaseMissing('profils', ['id' => $profil->id]);
    }
}
