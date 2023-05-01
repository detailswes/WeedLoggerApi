<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Str;
use DB;
use App\Models\EmailTemplate;
use App\Mail\ResetPassword;
use App\Models\User;
use App\Http\Requests\Auth\ResetPasswordEmailRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /* send reset password link through an email */
    /* use required and exists validation for requested email  */
    /* check in password resets table that email exist or not */
    /* if email exists  ,delete that record first ,then create new record */
    /* then send reset password link through an email  */
    public function ResetPasswordRequest(ResetPasswordEmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = Str::random(64);
        DB::table('password_resets')->where('email', $request->email)->delete();

        $saveToken = DB::table('password_resets')->updateOrInsert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        if ($saveToken) {
            $template = EmailTemplate::where('slug', 'forgot_password')->first();
            $body = $template->description;
            $subject = $template->subject;
            $tokenlink = $token;
            $today = Carbon::now();
            $logo = url('img/logo.png');
            $instagram = url('img/instagram.jpeg');
            $linkedin = url('img/linkedin-logo.png');
            $twitter = url('img/twitter.jpeg');
            $list = [
                '[Name]' => ucfirst($user->full_name),
                '[Logo]' => $logo,
                '[Footer_Logo]' => $logo,
                '[Year]' => $today->year,
                '[Subject]' => $subject,
                '[Email]' => $user->email,
                '[instagram]' => $instagram,
                '[linkedin]' => $linkedin,
                '[twitter]' =>  $twitter,
                '[Reset Password Link]' => url('api/reset-password/' . $tokenlink),
            ];

            $find = array_keys($list);
            $replace = array_values($list);
            $email_template = str_ireplace($find, $replace, $body);
            Mail::to($request["email"])->send(new ResetPassword($request->email, $token, $email_template));
        }

        return response()->json([
            'success' => true,
            'message' => "Please check your email for password reset."
        ]);
    }

    /* show password update form  */
    // public function ResetLoginPassword($token)
    // {
    //     return view('auth.reset-password', ['token' => $token]);
    // }

    /* update password using required validation for new password and confirm password */
    public function changePassword(UpdatePasswordRequest $request)
    {
        $user = DB::table('password_resets')->select('email')->where('token', $request->token)->first();
        if ($user) {
            User::where("email", $user->email)->update([
                'password' => bcrypt($request->new_password),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'success', 'You have successfully changed your password'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid token'
        ]);
    }
}
