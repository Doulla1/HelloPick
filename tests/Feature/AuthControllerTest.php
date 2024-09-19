<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_logs_in_an_admin_successfully_and_generates_token()
    {
        // Create an admin user with a hashed password
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'type' => 'administrateur',
        ]);


        // Send a request with the correct credentials
        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        // Check that the response has a 200 status code
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'token',
        ]);
    }

    #[Test]
    public function it_fails_login_with_invalid_credentials()
    {
        // Create a user with a hashed password
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('validpassword'),
        ]);

        // Send a request with the wrong password
        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        // Check that the response has a 401 status code
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }

    #[Test]
    public function it_fails_login_for_non_admin_user()
    {
        // Create a non-admin user with a hashed password
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'type' => 'user', // Pas un administrateur
        ]);

        // Send a request with the correct credentials
        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        // Check that the response has a 403 status code
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Unauthorized access',
        ]);
    }

    #[Test]
    public function it_logs_an_error_if_login_fails_due_to_exception()
    {
        // Simulate a failed login attempt with an exception
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'type' => 'administrateur',
        ]);

        // Simulate a database error during login
        Log::shouldReceive('error')->once()->withArgs(function ($message) {
            return str_contains($message, 'Login failed');
        });

        // Mock of Auth  to throw an exception
        Auth::shouldReceive('attempt')->andThrow(new \Exception('Database error'));

        // Send a request with the correct credentials
        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        // Check that the response has a 500 status code
        $response->assertStatus(500);
        $response->assertJson([
            'message' => 'Login failed. Please try again later.',
        ]);
    }
}
