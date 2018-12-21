<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Loan;

class TooMuchRepaymentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('passport:install');
        // $this->withoutExceptionHandling();
    }

    public function testTooMuch()
    {
        $this->assertTrue(true);
        $user = factory(User::class)->create();
        $loan = factory(Loan::class)->make([
            'amount' => 1000,
            'interest_rate' => 20,
            'duration' => 3
        ]);
        $user->loans()->save($loan);
        $i = 3;
        while($i > 0) {
            $response = $this->actingAs($user, 'api')
                             ->json('POST', route('repayment.store', [
                                 'amount' => 344.51,
                                 'loan_id' => $loan->id
                             ]));

            $response->assertStatus(200)
                        ->assertJsonStructure(['message', 'balance']);
            $i--;
        }

        // 4th payment (already paid at this point)
        $response = $this->actingAs($user, 'api')
                         ->json('POST', route('repayment.store', [
                             'amount' => 344.51,
                             'loan_id' => $loan->id
                         ]));
        $response->assertStatus(400)
                    ->assertExactJson(['error' => 'Repayment no longer accepted']);
    }
}

