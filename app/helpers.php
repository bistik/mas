<?php

if (!function_exists('compute_total')) {
    function compute_total($amount, $duration, $interest) {
        $total = $amount * (1 + (($interest / 100) / $duration)) ** ($duration * ($duration / 12));
        return round($total, 2);
    }
}
