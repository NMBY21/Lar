<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'order', 'payment collection', 'commission', 'expense',
            'vehicles', 'driver', 'location', 'load type', 'bank',
            'staffuser', 'role', 'give permission', 'report',
            'expense type', 'client'
        ];

        $actions = ['show', 'add', 'delete'];

        foreach ($permissions as $permission) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action} {$permission}"
                ]);
            }
        }
    }
}
