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

if (! function_exists('demo_data_visible')) {
    function demo_data_visible(): bool
    {
        try {
            return (bool) (Setting::current()->demo_data_visible ?? true);
        } catch (\Throwable $e) {
            return true;
        }
    }
}

if (! function_exists('schema_has_demo_column')) {
    function schema_has_demo_column(string $table): bool
    {
        static $cache = [];

        if (! array_key_exists($table, $cache)) {
            try {
                $cache[$table] = \Illuminate\Support\Facades\Schema::hasColumn($table, 'is_demo');
            } catch (\Throwable $e) {
                $cache[$table] = false;
            }
        }

        return $cache[$table];
    }
}

if (! function_exists('demo_data_exists')) {
    function demo_data_exists(): bool
    {
        try {
            return \App\Models\Supplier::withoutGlobalScopes()
                ->where('is_demo', true)
                ->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
