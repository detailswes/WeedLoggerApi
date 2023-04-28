<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::all();

        return response()->json([
            'message'=> $data,
            'success' => true,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
       $data = $this->userService->save($request);
        if($data['success']){
            $data['message'] = 'User Created Successfully!';
            return response()->json($data);
        }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if(isNull($user)){
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request['id'] = $id;
        $data = $this->userService->save($request);
        dd($data);
        if($data['success']){
            $data['message'] = 'User updated Successfully!';
            return response()->json($data);
        }
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
