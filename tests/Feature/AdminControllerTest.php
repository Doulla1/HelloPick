<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_an_administrator_successfully()
    {
        // create a new administrator
        $admin = User::factory()->create([
            'type' => 'administrateur',
        ]);

        // Authenticate the administrator
        $this->actingAs($admin, 'sanctum');

        // Send a request to create a new administrator
        $response = $this->postJson('/api/admins', [
            'email' => 'newadmin@example.com',
            'password' => 'securePassword123',
            'password_confirmation' => 'securePassword123',
        ]);

        // Check that the administrator was created successfully
        $response->assertStatus(201, "Expected status code 201 but got {$response->getStatusCode()}.");

        // Check that the administrator was stored in the database
        $this->assertDatabaseHas('users', [
            'email' => 'newadmin@example.com',
        ]);
    }

    #[Test]
    public function it_fails_to_create_an_administrator_if_not_admin()
    {
        // Create a non-admin user
        $user = User::factory()->create([
            'type' => 'user', // Ce n'est pas un administrateur
        ]);

        // Authenticate the user
        $this->actingAs($user, 'sanctum');

        // Send a request to create a new administrator (which should fail)
        $response = $this->postJson('/api/admins', [
            'email' => 'unauthorized@example.com',
            'password' => 'securePassword123',
            'password_confirmation' => 'securePassword123',
        ]);

        // Check that the request was forbidden
        $response->assertStatus(403, "Expected status code 403 but got {$response->getStatusCode()}.");
    }

}
