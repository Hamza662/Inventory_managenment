<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $guarded = [];

    public static function current(): self
    {
        return Cache::remember('portal_settings', 3600, function () {
            return static::query()->first() ?? static::query()->create([
                'store_name' => 'Inventory',
                'store_tagline' => 'Smart stock control',
                'store_email' => 'admin@gmail.com',
                'store_phone' => '',
                'store_address' => '',
                'currency_code' => 'PKR',
                'currency_symbol' => 'Rs',
                'footer_text' => 'Built for faster stock control.',
                'demo_data_visible' => true,
            ]);
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('portal_settings');
    }

    public function logoUrl(): string
    {
        if ($this->logo && file_exists(public_path($this->logo))) {
            return asset($this->logo);
        }

        return asset('favicon.png');
    }

    public function faviconUrl(): string
    {
        if ($this->favicon && file_exists(public_path($this->favicon))) {
            return asset($this->favicon);
        }

        if (file_exists(public_path('favicon.svg'))) {
            return asset('favicon.svg');
        }

        return asset('favicon.png');
    }
}
