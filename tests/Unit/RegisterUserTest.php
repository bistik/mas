<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUser extends TestCase
{
    public function testRegisterInvalid()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', route('auth.register'));

        $response->assertStatus(422)
                ->assertJsonStructure(['message', 'errors' => ['name', 'email', 'password']]);
    }

    public function testRegisterValid()
    {
        $name = 'register-' . time();
        $email = 'register-' . time() . '@test.com';
        $password = 'secret';
        $password_confirmation = 'secret';

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', route('auth.register', compact('name', 'email', 'password', 'password_confirmation')));

        $response->assertStatus(200)
                ->assertJson(['message' => 'User successfully created!']);
    }
}
