<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'Dashboard',
            ],
            [
                'name' => 'User',
            ],
            [
                'name' => 'Email',
            ],
            [
                'name' => 'Roles',
            ],
            [
                'name' => 'Company',
            ],
            [
                'name' => 'Installation',
            ],
            [
                'name' => 'Media',
            ],
        ];

        foreach ($permissions as $key => $permission) {
            Permission::updateOrCreate($permission);
        }
    }
}
