<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            'order', 'payment collection', 'commission', 'expense',
            'vehicles', 'driver', 'location', 'load type', 'bank',
            'staffuser', 'role', 'give permission', 'report',
            'expense type', 'client',
        ];

        $actions = ['show', 'add', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action} {$resource}"
                ]);
            }
        }
    }
}
