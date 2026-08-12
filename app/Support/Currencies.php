<?php

namespace App\Support;

class Currencies
{
    public static function all(): array
    {
        return [
            'PKR' => ['name' => 'Pakistan Rupee', 'symbol' => 'Rs'],
            'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
            'AED' => ['name' => 'UAE Dirham', 'symbol' => 'AED'],
            'SAR' => ['name' => 'Saudi Riyal', 'symbol' => 'SAR'],
            'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
            'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥'],
            'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
            'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$'],
            'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$'],
            'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF'],
            'TRY' => ['name' => 'Turkish Lira', 'symbol' => '₺'],
            'MYR' => ['name' => 'Malaysian Ringgit', 'symbol' => 'RM'],
            'SGD' => ['name' => 'Singapore Dollar', 'symbol' => 'S$'],
            'BDT' => ['name' => 'Bangladeshi Taka', 'symbol' => '৳'],
            'QAR' => ['name' => 'Qatari Riyal', 'symbol' => 'QAR'],
            'KWD' => ['name' => 'Kuwaiti Dinar', 'symbol' => 'KD'],
            'OMR' => ['name' => 'Omani Rial', 'symbol' => 'OMR'],
            'EGP' => ['name' => 'Egyptian Pound', 'symbol' => 'E£'],
        ];
    }

    public static function codes(): array
    {
        return array_keys(self::all());
    }

    public static function symbol(string $code): string
    {
        return self::all()[$code]['symbol'] ?? $code;
    }

    public static function label(string $code): string
    {
        $currency = self::all()[$code] ?? null;

        if (! $currency) {
            return $code;
        }

        return $code . ' — ' . $currency['name'] . ' (' . $currency['symbol'] . ')';
    }
}
