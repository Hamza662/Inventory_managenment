<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::clearCache();

        Setting::query()->firstOrCreate(
            ['id' => 1],
            [
                'store_name' => 'Inventory',
                'store_tagline' => 'Smart stock control',
                'store_email' => 'admin@gmail.com',
                'store_phone' => '',
                'store_address' => '',
                'currency_code' => 'PKR',
                'currency_symbol' => 'Rs',
                'footer_text' => 'Built for faster stock control.',
            ]
        );
    }
}
