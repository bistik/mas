<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() {
        parent::setUp();
        $this->artisan('passport:install');
    }

    public function testInvalidLogin()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', route('login'));

        $response->assertStatus(422)
                ->assertJsonStructure(['message', 'errors' => ['email', 'password']]);
    }

    public function testValidLogin()
    {
        $user = factory(User::class)->create();
        $password = 'secret';

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', route('login', [
            'email' => $user->email,
            'password' => $password
        ]));

        $response->assertStatus(200)
                    ->assertJsonStructure(['access_token', 'token_type', 'expires_at']);
    }
}
