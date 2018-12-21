<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Loan;

class ShowLoansTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testEmpty()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')
                         ->json('GET', route('loan.all'));

        $response->assertStatus(200)
                 ->assertExactJson([
                     'loans' => []
                 ]);
    }

    public function testWithLoans()
    {
        $user = factory(User::class)->create();
        $loan = factory(Loan::class)->make();
        $user->loans()->save($loan);

        $response = $this->actingAs($user, 'api')
                         ->json('GET', route('loan.all'));

        $response->assertStatus(200);
        $json = json_decode($response->content());
        $this->assertFalse(empty($json));
        $this->assertFalse(empty($json->loans));
        $this->assertEquals($json->loans[0]->user_id, $user->id);
        $this->assertEquals($json->loans[0]->amount, $loan->amount);
        $this->assertEquals($json->loans[0]->duration, $loan->duration);
        $this->assertEquals($json->loans[0]->repayment_frequency, $loan->repayment_frequency);
        $this->assertEquals($json->loans[0]->monthly_repayment, round($loan->monthly_repayment, 2));
        $this->assertEquals($json->loans[0]->interest_rate, round($loan->interest_rate, 2));
    }
}
