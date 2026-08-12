<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            static $settings = null;

            if ($settings === null) {
                try {
                    $settings = Schema::hasTable('settings')
                        ? Setting::current()
                        : new Setting([
                            'store_name' => 'Inventory',
                            'store_tagline' => 'Smart stock control',
                            'currency_code' => 'PKR',
                            'currency_symbol' => 'Rs',
                            'footer_text' => 'Built for faster stock control.',
                        ]);
                } catch (\Throwable $e) {
                    $settings = new Setting([
                        'store_name' => 'Inventory',
                        'store_tagline' => 'Smart stock control',
                        'currency_code' => 'PKR',
                        'currency_symbol' => 'Rs',
                        'footer_text' => 'Built for faster stock control.',
                    ]);
                }
            }

            $view->with('portalSettings', $settings);
        });
    }
}
