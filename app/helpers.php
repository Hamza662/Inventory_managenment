<?php

use App\Models\Setting;
use App\Support\Currencies;

if (! function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        try {
            return Setting::current()->currency_symbol ?: 'Rs';
        } catch (\Throwable $e) {
            return 'Rs';
        }
    }
}

if (! function_exists('currency_code')) {
    function currency_code(): string
    {
        try {
            return Setting::current()->currency_code ?: 'PKR';
        } catch (\Throwable $e) {
            return 'PKR';
        }
    }
}

if (! function_exists('money')) {
    function money($amount, int $decimals = 0): string
    {
        return currency_symbol() . number_format((float) $amount, $decimals);
    }
}

if (! function_exists('currencies_list')) {
    function currencies_list(): array
    {
        return Currencies::all();
    }
}
