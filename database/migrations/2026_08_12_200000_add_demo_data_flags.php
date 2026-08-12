<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'units',
            'suppliers',
            'customers',
            'categories',
            'products',
            'buys',
            'buy_product',
            'invoices',
            'items',
            'payments',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'is_demo')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->boolean('is_demo')->default(false)->after('id');
                    $blueprint->index('is_demo');
                });
            }
        }

        if (Schema::hasTable('settings') && ! Schema::hasColumn('settings', 'demo_data_visible')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->boolean('demo_data_visible')->default(true)->after('footer_text');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'units',
            'suppliers',
            'customers',
            'categories',
            'products',
            'buys',
            'buy_product',
            'invoices',
            'items',
            'payments',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'is_demo')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropIndex(['is_demo']);
                    $blueprint->dropColumn('is_demo');
                });
            }
        }

        if (Schema::hasTable('settings') && Schema::hasColumn('settings', 'demo_data_visible')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('demo_data_visible');
            });
        }
    }
};
