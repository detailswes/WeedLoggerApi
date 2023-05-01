<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::select('id', 'role_id')->where('email', 'admin@admin.com')->first();
        if ($user) {
            UserRole::updateOrCreate([
                'user_id' => $user->id,
                'role_id' => $user->role_id
            ]);
        }
    }
}
