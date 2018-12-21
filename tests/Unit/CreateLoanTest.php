<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class CreateLoanTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() {
        parent::setUp();
        //$this->withoutExceptionHandling();
    }

    public function testInvalid()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')
                         ->json('POST', route('loan.store', [
                                            'amount' => '',
                                            'interest_rate' => '',
                                            'duration' => '',
                                            'repayment_frequency' => '']));
        $response->assertStatus(422)
                    ->assertJsonStructure(['message', 'errors' => ['amount', 'interest_rate', 'duration', 'repayment_frequency']]);
    }

    public function testValid()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')
                         ->json('POST', route('loan.store', [
                             'amount' => 10000,
                             'interest_rate' => 10,
                             'duration' => 12,
                             'repayment_frequency' => 1,
                         ]));

        $response->assertStatus(200);
        $this->assertDatabaseHas('loans', ['user_id' => $user->id,
                                            'amount' => 10000.00,
                                            'interest_rate' => 10.00,
                                            'duration' => 12,
                                            'monthly_repayment' => 879.16,
                                            'repayment_frequency' => 1]);
    }
}
