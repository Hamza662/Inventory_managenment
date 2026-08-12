<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Support\PermissionTree;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (PermissionTree::groups() as $permissions) {
            foreach ($permissions as $name => $label) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web']
                );
            }
        }

        $superAdmin = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'web'],
            ['description' => 'Full access to inventory portal']
        );

        $admin = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'web'],
            ['description' => 'Manage inventory operations']
        );

        $agent = Role::firstOrCreate(
            ['name' => 'Agent', 'guard_name' => 'web'],
            ['description' => 'Limited inventory access']
        );

        $allPermissions = Permission::where('guard_name', 'web')->pluck('name')->all();
        $superAdmin->syncPermissions($allPermissions);

        $admin->syncPermissions(collect($allPermissions)->reject(
            fn ($name) => in_array($name, ['roles.delete', 'users.delete', 'settings.manage'], true)
        )->values()->all());

        $agent->syncPermissions([
            'dashboard.view',
            'products.view',
            'customers.view',
            'invoices.view',
            'invoices.create',
            'stock.view',
        ]);

        $user = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'user_name' => 'superadmin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $user->syncRoles([$superAdmin]);
        $user->syncPermissions($allPermissions);
    }
}
