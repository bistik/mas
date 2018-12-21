<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Loan;

class CreateRepaymentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('passport:install');
        // $this->withoutExceptionHandling();
    }

    public function testInvalid()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')
                         ->json('POST', route('repayment.store', ['loan_id' => '', 'amount' => '']));

        $response->assertStatus(422)
                    ->assertJsonStructure(['message', 'errors' => ['loan_id', 'amount'] ]);

        $response = $this->actingAs($user, 'api')
                         ->json('POST', route('repayment.store', ['loan_id' => time(), 'amount' => 1.23])); // non-existing loan

        $response->assertStatus(422)
                    ->assertJsonStructure(['message', 'errors' => ['loan_id'] ]);

    }

    public function testValid()
    {
        $user = factory(User::class)->create();
        $loan = factory(Loan::class)->make();
        $user->loans()->save($loan);

        $response = $this->actingAs($user, 'api')
                         ->json('POST', route('repayment.store', [
                             'amount' => round($loan->monthly_repayment, 2),
                             'loan_id' => $loan->id
                         ]));

        $response->assertStatus(200)
                    ->assertJsonStructure(['message', 'balance']);

        $this->assertDatabaseHas('repayments', ['user_id' => $user->id,
                                                'loan_id' => $loan->id]);
    }
}
