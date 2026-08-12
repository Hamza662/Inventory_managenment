<?php

namespace Database\Seeders;

use App\Support\DemoData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (DemoData::exists()) {
            DemoData::setVisible(true);
            $this->command?->info('Demo data already exists — visibility restored.');

            return;
        }

        Model::unguarded(function () {
            DemoData::seed();
        });
        DemoData::setVisible(true);

        $this->command?->info('Demo inventory data seeded.');
    }
}
