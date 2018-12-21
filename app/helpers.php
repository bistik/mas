<?php

if (!function_exists('compute_monthly_repayment')) {
    function compute_monthly_repayment($amount, $interest, $duration) {
        // source: https://www.vertex42.com/ExcelArticles/amortization-calculation.html
        $r = $interest / 100 / 12;
        $n = $duration;
        $P = $amount;
        $top = (1 + $r) ** $n * $r;
        $bot = (1 + $r) ** $n - 1;

        return $top / $bot * $P;
    }
}

if (!function_exists('compute_interest')) {
    function compute_interest($interest, $balance) {
        $r = $interest / 100 / 12;

        return $balance * $r;
    }
}
