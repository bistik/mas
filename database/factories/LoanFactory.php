<?php

use Faker\Generator as Faker;

$factory->define(App\Loan::class, function (Faker $faker) {

    $amount = random_int(1, 100) * 1000;
    $interest_rate = random_int(3,20);
    $duration = random_int(1,10) * 12;
    $monthly_repayment = compute_monthly_repayment($amount, $interest_rate, $duration);

    return [
        'amount' => $amount,
        'monthly_repayment' => $monthly_repayment,
        'interest_rate' => $interest_rate,
        'duration' => $duration,
        'repayment_frequency' => 1,
        'currency' => $faker->currencyCode
    ];
});
