<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Jobs\TestSendEmail;
use App\Jobs\Welcome;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        // $payload = $this->getPayload($token);
        dd($payload);
        if(Auth::user()->can('user_listing')){
            $data = User::all();

            return response()->json([
                'message'=> $data,
                'success' => true,
            ]);
        }
        abort(403, 'You are not authorized.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {

        if(Auth::user()->can('user_create')){
            $data = $this->userService->save($request);
            if($data['success']){
                $data['message'] = 'User Created Successfully!';
                return response()->json($data);
            }
            return response()->json($data);
        }
        abort(403, 'You are not authorized.');

    }

    public function show(string $id)
    {
        // dd("here");
        if(Auth::user()->can('user_edit')){
            $user = User::find($id);
            if(is_null($user)){
                return response()->json([
                    'success' => false,
                    'message' => "User not found with this id",
                    'user' => null
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'User found',
                'user' => $user
            ]);
        }
        abort(403, 'You are not authorized.');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, string $id)
    {
        if(Auth::user()->can('user_edit')){
            $data = $this->userService->save($request);
            if($data['success']){
                $data['message'] = 'User updated Successfully!';
                return response()->json($data,201);
            }
            return response()->json($data,422);
        }
        abort(403, 'You are not authorized.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sendTestEmails(Request $request)
    {
        // dd('here');
        $email = new TestHelloEmail();
        Mail::to('dishanpreet.webethics@gmail.com')->send($email);
        $email = 'dishanpreet@gmail.com';
        Welcome::dispatch($email);

    }
}
