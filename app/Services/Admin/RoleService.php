<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\RolePermission;
use App\Models\Permission;

class RoleService
{
    public function save(Request $request)
    {
        $data = Role::updateOrCreate([
            'id' => $request->id,
        ], [
            'name' => $request->name,
            'slug' => slugify($request->name),
        ]);
        if ($data) {
            if ($request->permissions != null) {
                $permissionList = $request->permissions;
                $checkPermissions = RolePermission::where('role_id', $data->id);
                if ($checkPermissions->exists()) {
                    $oldPermissions = $checkPermissions->delete();
                    $this->storeRole($permissionList, $data);
                } else {
                    $this->storeRole($permissionList, $data);
                }
            }
            if ($request->permissions == null) {
                $checkPermissions = RolePermission::where('role_id', $data->id);
                if ($checkPermissions->exists()) {
                    $oldPermissions = $checkPermissions->delete();
                    return $data;
                }
            }
        }
    }

    private function storeRole($permissionList, $data)
    {
        foreach ($permissionList as $getPermissionListId) {
            $rolePermission = [
                'role_id' => $data->id,
                'permission_id' => $getPermissionListId
            ];
            RolePermission::updateOrCreate($rolePermission);
        }
    }

    public function delete($id)
    {
        RolePermission::where('role_id', $id)->delete();
        Role::where('id', $id)->delete();
        return [
            'status' => true,
            'message' => 'Role and Permission deleted successfully'
        ];
    }

    public function renderModalHTML(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $roles = Role::with('permissions')->where('id', $id)->first();
            $permissionArray = array();
            foreach ($roles->permissions as $permission) {
                $permissionArray[] = $permission->permission_id;
                $roles->permissionArray = $permissionArray;
            }
        }
        /* Permission with list of all permissions */
        $permissionListData = Permission::with('lists')->get();
        return view($request->view, [
            'roles' => $roles ?? null,
            'permissionListData' => $permissionListData ?? null,
        ])->render();
    }
}
