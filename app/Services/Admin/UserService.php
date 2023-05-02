<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\StoreUserRequest;
use App\Jobs\Welcome as JobsWelcome;
use App\Mail\Welcome;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\UserRole;
use Carbon\Carbon;
use Str;
use DB;
use Illuminate\Support\Facades\Mail;

class UserService
{

    public function save(StoreUserRequest $request)
    {
        // dd(url('img/logo.png'));
        $requestData = [
            'email' => $request->email,
            'full_name' => $request->full_name,
            'role_id' => $request->role_id,
        ];

        if ($request->password) {
            $requestData['password'] =  bcrypt($request->password);
        }
        $data = User::updateOrCreate([
            'id' => $request->id
        ], $requestData);

        if ($data) {
            $checkUserRole = UserRole::where('user_id', $data->id);

            if ($checkUserRole->exists()) {
                $userRoleDelete = $checkUserRole->delete();
                $createUserRole = [
                    'user_id' => $data->id,
                    'role_id' => $request->role_id
                ];
                UserRole::create($createUserRole);
                return [
                    'success' => true,
                    "message" => "User Register Successfully !!",
                    'user' => $data
                ];
            } else {
                $createUserRole = [
                    'user_id' => $data->id,
                    'role_id' => $request->role_id
                ];
                UserRole::create($createUserRole);
                return [
                    'success' => true,
                    "message" => "User Register Successfully !!",
                    'user' => $data
                ];
            }
        }else{
            return [
                'success' => false,
                "message" => "Something Went Wrong !!!",
                'user' => null
            ];
        }
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
