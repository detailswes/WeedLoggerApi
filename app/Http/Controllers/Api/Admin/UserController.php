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

    /**
        * @OA\Get(
        *      path="/api/users",
        *      operationId="Users Listing",
        *      tags={"Users"},
        *      summary="All Users Listing",
        *      description="All Users Listing Here",
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="List of all users retrieved successfully."),
        *      @OA\Response(response=403, description="You are not authorized."),
        *     )
    */
    public function index(Request $req)
    {
        // $payload = $this->getPayload($token);
        // dd($payload);
        if(Auth::user()->can('user_listing')){
            $data = User::with('company','role')->orderBy('id', 'DESC')->get();
            return response()->json([
                'data'=> $data,
                'success' => true,
            ]);
        }
        abort(403, 'You are not authorized.');
    }

    /**
     * Store a newly created resource in storage.
    */

    /**
        * @OA\Post(
        *      path="/api/users",
        *      operationId="Create User",
        *      tags={"Users"},
        *      summary="Create User",
        *      description="Create single user Here",
        *      @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"fullName", "email", "password", "roleId", "companyId"},
        *               @OA\Property(property="fullName", type="string"),
        *               @OA\Property(property="email", type="string", format="email"),
        *               @OA\Property(property="password", type="string", writeOnly=true),
        *               @OA\Property(property="roleId", type="integer"),
        *               @OA\Property(property="companyId", type="integer"),
        *            ),
        *        ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="User Created Successfully!"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *     )
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

    /**
        * @OA\Get(
        *      path="/api/users/{id}",
        *      operationId="Single User",
        *      tags={"Users"},
        *      summary="Get Single User Using Id",
        *      description="Get single user here using id",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Show user by id",
        *         required=true,
        *         @OA\Schema(
        *               type="integer",
        *         ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="User found successfully."),
        *      @OA\Response(response=403, description="You are not authorized."),
        *     )
    */
    public function show(string $id)
    {
        // dd("here");
        if(Auth::user()->can('user_edit')){
            $user = User::with('company','role')->find($id);
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

    /**
        * @OA\Put(
        *      path="/api/users/{id}",
        *      operationId="UpdateUser",
        *      tags={"Users"},
        *      summary="Update a user's details by ID",
        *      description="Update a user's details by ID",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the company to update",
        *         required=true,
        *         @OA\Schema(
        *               type="integer",
        *         ),
        *      ),
        *      @OA\RequestBody(
        *         @OA\MediaType(
        *            mediaType="application/json",
        *            @OA\Schema(
        *               type="object",
        *               required={"fullName", "email", "password", "roleId", "companyId"},
        *               @OA\Property(property="fullName", type="string"),
        *               @OA\Property(property="email", type="string", format="email"),
        *               @OA\Property(property="password", type="string", writeOnly=true),
        *               @OA\Property(property="roleId", type="integer"),
        *               @OA\Property(property="companyId", type="integer"),
        *            ),
        *        ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="User updated successfully"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *     )
    */
    public function update(StoreUserRequest $request, string $id)
    {
        if(Auth::user()->can('user_edit')){
            $request->merge(['id'=>$id]);
            $data = $this->userService->save($request);
            if($data['success']){
                $data['message'] = 'User updated successfully!';
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
        Mail::to('taranjeet.webethics@gmail.com')->send($email);
        $email = 'taranjeet.webethics@gmail.com';
        Welcome::dispatch($email);
    }
}
