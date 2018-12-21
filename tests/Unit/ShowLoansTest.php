<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

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

        $response = $this->actingAs($user, 'api')
                         ->json('POST', route('loan.store', [
                             'amount' => 5000,
                             'interest_rate' => 10,
                             'duration' => 6,
                             'repayment_frequency' => 1,
                         ]));

        $response->assertStatus(200);

        $response = $this->actingAs($user, 'api')
                         ->json('GET', route('loan.all'));

        $response->assertStatus(200);
        $json = json_decode($response->content());
        $this->assertFalse(empty($json));
        $this->assertFalse(empty($json->loans));
        $this->assertEquals($json->loans[0]->user_id, $user->id);
        $this->assertEquals($json->loans[0]->amount, 5000.00);
        $this->assertEquals($json->loans[0]->duration, 6);
        $this->assertEquals($json->loans[0]->repayment_frequency, 1);
        $this->assertEquals($json->loans[0]->interest_rate, 10.00);
    }
}
