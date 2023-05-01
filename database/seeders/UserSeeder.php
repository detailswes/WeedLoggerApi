<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRoleId = Role::where('slug', 'super-admin')->first()->id;

        $adminUsers = [
            [
              'email' => 'admin@admin.com',
              'full_name' => 'Super Admin',
            ]
        ];

        $users = [];

        foreach ($adminUsers as $key => $adminUser) {
            User::updateOrCreate([
                'email' => $adminUser['email']
            ], [
                'full_name' => $adminUser['full_name'],
                'role_id' => $adminRoleId,
                'password' => bcrypt('Teamwebethics3!'),
                'email_verified_at' => now(),
                'status' => 1
            ]);
        }
    }
}
