<?php

if (!function_exists('compute_total')) {
    function compute_total($amount, $duration, $interest) {
        $total = $amount * (1 + (($interest / 100) / $duration)) ** ($duration * ($duration / 12));
        return round($total, 2);
    }
}

if (!function_exists('compute_monthly_repayment')) {
    function compute_monthly_repayment($amount, $interest, $duration) {
        // source: https://www.vertex42.com/ExcelArticles/amortization-calculation.html
        $r = $interest / 100 / 12;
        $n = $duration;
        $P = $amount;
        $top = (1 + $r) ** $n * $r;
        $bot = (1 + $r) ** $n - 1;

        return number_format($top / $bot * $P, 2, '.', '');
    }
}
