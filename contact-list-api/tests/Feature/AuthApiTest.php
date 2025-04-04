<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = app(AuthService::class);
    }

    public function test_user_can_resgister()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }
    /**
     * A basic feature test example.
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('test1234'),
        ]);

        $credentials = [
            'email' => 'test@gmail.com',
            'password' => 'test1234',
        ];

        $response = $this->authService->login($credentials);
        $this->assertArrayHasKey('user', $response);
        $this->assertArrayHasKey('token', $response);
    }

    public function test_can_loging_user()
    {
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('test1234'),
        ]);

        $loginData = [
            'email' => 'test@gmail.com',
            'password' => 'test1234',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'token',
            ]);
    }

    public function test_can_logout_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out']);

        $this->assertEmpty($user->tokens);
    }

    public function test_invalid_user_validation()
    {
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('test1234'),
        ]);

        $invalidData = [
            'email' => 'test@gmail.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $invalidData);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Invalid credentials.']);
    }
}
