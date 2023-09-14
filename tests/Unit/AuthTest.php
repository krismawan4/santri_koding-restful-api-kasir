<?php

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_success()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@santrikoding.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'created_at',
                    'updated_at',
                ],
                'access_token',
                'token_type',
                'expires_in',
            ],
            'error',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => 1,
                    'name' => 'Admin Oke',
                    'email' => 'admin@santrikoding.com',
                    'role' => 'admin',
                    'created_at' => null,
                    'updated_at' => null,
                ],
                'token_type' => 'bearer',
                'expires_in' => 10080,
            ],
            'error' => '',
        ]);
    }

    public function test_login_failed()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
            'error',
        ]);
        $response->assertJson([
            'message' => 'Login Gagal',
        ]);
    }
}
