<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Permission::get()->pluck('id', 'name')->toArray();
        $dashBoardPermissionId = $roles['Dashboard'];
        $userPermissionId = $roles['User'];
        $emailPermissionId = $roles['Email'];
        $rolePermissionId = $roles['Roles'];
        $companyPermissionId = $roles['Company'];
        $installationPermissionId = $roles['Installation'];
        $mediaPermissionId = $roles['Media'];
        $permissions = [
            [
                'permission_id' => $dashBoardPermissionId,
                'name' => 'Dashboard :Listing',
                'slug' => 'dashboard_listing'
            ],
            [
                'permission_id' => $userPermissionId,
                'name' => 'User:Listing',
                'slug' => 'user_listing'
            ],
            [
                'permission_id' => $userPermissionId,
                'name' => 'User :Create',
                'slug' => 'user_create'
            ],
            [
                'permission_id' => $userPermissionId,
                'name' => 'User : Edit',
                'slug' => 'user_edit'
            ],
            [
                'permission_id' => $userPermissionId,
                'name' => 'User :Set User As Super Admin',
                'slug' => 'set_user_as_super_admin'
            ],
            [
                'permission_id' => $userPermissionId,
                'name' => 'User :Set User As Admin',
                'slug' => 'set_user_as_admin'
            ],
            [
                'permission_id' => $userPermissionId,
                'name' => 'User :Delete',
                'slug' => 'user_delete'
            ],
            [
                'permission_id' =>  $emailPermissionId,
                'name' => 'Email: Listing',
                'slug' => 'email_listing'
            ],
            [
                'permission_id' => $emailPermissionId,
                'name' => 'Email:Create',
                'slug' => 'email_create'
            ],
            [
                'permission_id' =>  $emailPermissionId,
                'name' => 'Email:Edit',
                'slug' => 'email_edit'
            ],
            [
                'permission_id' =>  $emailPermissionId,
                'name' => 'Email: Delete',
                'slug' => 'email_delete'
            ],
            [
                'permission_id' =>  $rolePermissionId,
                'name' => 'Roles: Listing',
                'slug' => 'roles_listing'
            ],
            [
                'permission_id' => $rolePermissionId,
                'name' => 'Roles:Create',
                'slug' => 'roles_create'
            ],
            [
                'permission_id' => $rolePermissionId,
                'name' => 'Roles:Edit',
                'slug' => 'roles_edit'
            ],
            [
                'permission_id' => $rolePermissionId,
                'name' => 'Roles: Delete',
                'slug' => 'roles_delete'
            ],
            [
                'permission_id' => $mediaPermissionId,
                'name' => 'Media: Listing',
                'slug' => 'media_listing'
            ],
            [
                'permission_id' => $mediaPermissionId,
                'name' => 'Media: Create',
                'slug' => 'media_create'
            ],
            [
                'permission_id' => $mediaPermissionId,
                'name' => 'Media: Delete',
                'slug' => 'media_delete'
            ],
            [
                'permission_id' => $installationPermissionId,
                'name' => 'Installation:Listing',
                'slug' => 'installation_listing'
            ],
            [
                'permission_id' => $installationPermissionId,
                'name' => 'Installation: Create',
                'slug' => 'installation_create'
            ],
            [
                'permission_id' => $installationPermissionId,
                'name' => 'Installation:Edit',
                'slug' => 'installation_edit'
            ],
            [
                'permission_id' => $installationPermissionId,
                'name' => 'Installation:Delete',
                'slug' => 'installation_delete'
            ],
            [
                'permission_id' =>  $companyPermissionId,
                'name' => 'Company: Listing',
                'slug' => 'company_listing'
            ],
            [
                'permission_id' => $companyPermissionId,
                'name' => 'Company:Create',
                'slug' => 'company_create'
            ],
            [
                'permission_id' =>  $companyPermissionId,
                'name' => 'Company:Edit',
                'slug' => 'company_edit'
            ],
            [
                'permission_id' =>  $companyPermissionId,
                'name' => 'Company: Delete',
                'slug' => 'company_delete'
            ],
        ];

        foreach ($permissions as $key => $permission) {
            PermissionList::updateOrCreate($permission);
        }
    }
}
