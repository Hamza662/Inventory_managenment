<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Kept for compatibility — permissions + super admin are seeded by PermissionSeeder.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
    }
}
