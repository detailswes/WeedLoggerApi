<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\PermissionList;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Auth;

use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // echo $user;
        // die;
        try {
            PermissionList::get()->map(function ($permissionList) {

                Gate::define($permissionList->slug, function ($user) use ($permissionList) {
                    return $user->hasPermissionTo($permissionList, $user);
                });
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole([$role]);
        });
    }
}
