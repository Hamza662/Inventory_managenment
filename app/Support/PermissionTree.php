<?php

namespace App\Support;

class PermissionTree
{
    public static function groups(): array
    {
        return [
            'Dashboard' => [
                'dashboard.view' => 'View dashboard',
            ],
            'Suppliers' => [
                'suppliers.view' => 'View suppliers',
                'suppliers.create' => 'Create suppliers',
                'suppliers.edit' => 'Edit suppliers',
                'suppliers.delete' => 'Delete suppliers',
            ],
            'Customers' => [
                'customers.view' => 'View customers',
                'customers.create' => 'Create customers',
                'customers.edit' => 'Edit customers',
                'customers.delete' => 'Delete customers',
            ],
            'Units' => [
                'units.view' => 'View units',
                'units.create' => 'Create units',
                'units.edit' => 'Edit units',
                'units.delete' => 'Delete units',
            ],
            'Categories' => [
                'categories.view' => 'View categories',
                'categories.create' => 'Create categories',
                'categories.edit' => 'Edit categories',
                'categories.delete' => 'Delete categories',
            ],
            'Products' => [
                'products.view' => 'View products',
                'products.create' => 'Create products',
                'products.edit' => 'Edit products',
                'products.delete' => 'Delete products',
            ],
            'Purchases' => [
                'purchases.view' => 'View purchases',
                'purchases.create' => 'Create purchases',
                'purchases.approve' => 'Approve purchases',
                'purchases.delete' => 'Delete purchases',
            ],
            'Invoices' => [
                'invoices.view' => 'View invoices',
                'invoices.create' => 'Create invoices',
                'invoices.approve' => 'Approve invoices',
                'invoices.delete' => 'Delete invoices',
            ],
            'Stock' => [
                'stock.view' => 'View stock reports',
            ],
            'Settings' => [
                'settings.manage' => 'Manage portal settings',
            ],
            'Roles' => [
                'roles.view' => 'View roles',
                'roles.create' => 'Create roles',
                'roles.edit' => 'Edit roles',
                'roles.delete' => 'Delete roles',
            ],
            'Users' => [
                'users.view' => 'View users',
                'users.create' => 'Create users',
                'users.edit' => 'Edit users',
                'users.delete' => 'Delete users',
                'users.permissions' => 'Assign user permissions',
            ],
        ];
    }

    public static function allNames(): array
    {
        $names = [];

        foreach (self::groups() as $permissions) {
            foreach (array_keys($permissions) as $name) {
                $names[] = $name;
            }
        }

        return $names;
    }
}
