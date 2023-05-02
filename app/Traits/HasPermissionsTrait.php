<?php

namespace App\Traits;

use App\Models\PermissionList;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

trait HasPermissionsTrait
{

    public function givePermissionsTo(...$permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if ($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    public function withdrawPermissionsTo($permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        return $this->permissions()->detach($permissions);
    }

    public function refreshPermissions(...$permissions)
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

    public function hasPermissionTo($permissionList, $user)
    {
        return  $this->hasPermission($permissionList, $user);
    }

    public function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole(...$roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('name', $role)) {
                return true;
            }
        }
        return false;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    protected function hasPermission($permissionList, $user)
    {
        return (bool) $user->role->permissions->where('permission_id', $permissionList->id)->first();
    }

    public function getAllPermissions($user)
    {
        return $user->role->permissions;
    }
}
