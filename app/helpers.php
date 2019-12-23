<?php

if (! function_exists('currency_symbol')) {
    function currency_symbol()
    {
        $currency = config('cashier.currency');
        $symbols = config('subscription.currency_symbols');

        return $symbols[$currency];
    }
}

if (! function_exists('money')) {
    function money($value)
    {
        return $value / 100;
    }
}
