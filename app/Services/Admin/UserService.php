<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\UserRole;

class UserService
{

    public function save(StoreUserRequest $request)
    {
        $requestData = [
            'email' => $request->email,
            'full_name' => $request->full_name,
            'role_id' => $request->role_id,
        ];
        $checkRole = Role::find($request->role_id);
        if(is_null($checkRole)){
            return [
                'success' => false,
                'message' => "please enter valid role id",
                'user' => null
            ];
        }
            if ($request->password) {
                $requestData['password'] =  bcrypt($request->password);
            }
            $data = User::updateOrCreate([
                'id' => $request->id
            ], $requestData);
            return [
                'success' => true,
                'user' => $data
            ];
    }

    public function delete($id)
    {
        UserRole::where('user_id', $id)->delete();
        return User::where('id', $id)->delete();
    }

    public function updateStatus(Request $request)
    {
        return User::findOrFail($request->id)->update([
            'status' => $request->status
        ]);
    }

    public function renderModalHTML(Request $request)
    {
        $roles = Role::all();
        $user = User::find($request->id);

        if ($user) {
            $user->nextAndPrevious();
        }

        return view($request->view, [
            'roles' => Role::all(),
            'user' => $user ?? null,
        ])->render();
    }
}
