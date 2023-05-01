<?php

namespace Database\Seeders;

use App\Models\PermissionList;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePerimissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = Role::where('slug', 'super-admin')->orWhere('slug', 'user')->orWhere('slug', 'admin')->get();
        foreach ($roles as  $role) {
            if ($role->slug == 'super-admin') {
                $permissions = PermissionList::get()->pluck('id', 'name')->toArray();
            } elseif ($role->slug == 'user') {
                $slugs = ['dashboard_listing'];
                $permissions = PermissionList::whereIn('slug', $slugs)->get()->pluck('id', 'name')->toArray();
            } elseif ($role->slug == 'admin') {
                $slugs = ['set_user_as_super_admin','company_listing','company_create','company_edit','company_delete'];
                $permissions = PermissionList::whereNotIn('slug', $slugs)->get()->pluck('id', 'name')->toArray();
            }
            foreach ($permissions as $permission) {
                RolePermission::updateOrCreate([
                    'role_id' => $role->id,
                    'permission_id' => $permission
                ]);
            }
        }
    }
}
