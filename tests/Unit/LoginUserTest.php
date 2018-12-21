<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginUserTest extends TestCase
{
    public function testInvalidLogin()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', route('auth.login'));

        $response->assertStatus(422)
                ->assertJsonStructure(['message', 'errors' => ['email', 'password']]);
    }

    public function testValidLogin()
    {
        $name = 'login-' . time();
        $email = 'login-' . time() . '@test.com';
        $password = 'secret';
        $password_confirmation = 'secret';

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', route('auth.register', compact('name', 'email', 'password', 'password_confirmation')));

        $response->assertStatus(200);

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', route('auth.login', compact('email', 'password')));

        $response->assertStatus(200)
                    ->assertJsonStructure(['access_token', 'token_type', 'expires_at']);
    }
}
