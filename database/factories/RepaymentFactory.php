<?php

use Faker\Generator as Faker;

$factory->define(App\Repayment::class, function (Faker $faker) {
    return [
        'payment' => 1.00,
        'interest' => 1.00,
        'principal' => 1.00,
        'start_balance' => 1.00,
        'end_balance' => 1.00
    ];
});
