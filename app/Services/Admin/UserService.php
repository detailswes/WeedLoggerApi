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
        // $emailJobs = new JobsWelcome();
        $data = User::updateOrCreate([
            'id' => $request->id
        ], $requestData);
        if($data){
            $template = EmailTemplate::where('slug', 'Welcome_&reset_password')->first();
            $today = Carbon::now();
    
            $body = $template->description;
            $subject = $template->subject;
            $token = Str::random(64);
            $tokenlink = $token;
    
            $logo = url('img/logo.jpeg');
            $instagram = url('img/instagram.jpeg');
            $linkedin = url('img/linkedin-logo.png');
            $twitter = url('img/twitter.jpeg');
            $list = [
                '[Name]' => ucfirst($request->full_name),
                '[Logo]' => $logo,
                '[Year]' => $today->year,
                '[Footer_Logo]' => $logo,
                '[Subject]' => $subject,
                '[instagram]' => $instagram,
                '[linkedin]' => $linkedin,
                '[twitter]' =>  $twitter,
                '[Reset Password Link]' => url('api/reset-password/' . $tokenlink),
            ];
            $find = array_keys($list);
            $replace = array_values($list);
            $email_template = str_ireplace($find, $replace, $body);
            Mail::to($data['email'])->send(new Welcome($email_template));
        }

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
                    'user' => $data
                ];
            }
        }else{
            return [
                'success' => false,
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
