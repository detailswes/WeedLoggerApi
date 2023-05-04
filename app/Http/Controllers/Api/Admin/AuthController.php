<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
    */

    /**
        * @OA\Post(
        *      path="/api/auth/login",
        *      operationId="Auth Login",
        *      tags={"Authentication"},
        *      summary="User Login",
        *      description="User Login Here",
        *      @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password"),
        *            ),
        *        ),
        *      ),
        *      @OA\Response(response=200, description="Verify Your Email & Password"),
        *      @OA\Response(response=401, description="Unauthenticated"),
        *      @OA\Response(response=403, description="Forbidden"),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        *     )
    */
    public function login(LoginRequest $request){
        if (! $token = auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Verify Your Email & Password'
            ], 401);
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
    */

    /**
        * @OA\Post(
        *      path="/api/auth/register",
        *      operationId="Auth Registeration",
        *      tags={"Authentication"},
        *      summary="User Registeration",
        *      description="User Registeration Here",
        *      @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"fullName", "email", "password", "roleId", "companyId"},
        *               @OA\Property(property="fullName", type="text"),
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password"),
        *               @OA\Property(property="roleId", type="number"),
        *               @OA\Property(property="companyId", type="number"),
        *            ),
        *        ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="User successfully registered"),
        *      @OA\Response(response=401, description="Unauthenticated"),
        *      @OA\Response(response=403, description="Forbidden"),
        *      @OA\Response(response=422, description="Bad request"),
        *     )
    */
    public function register(StoreUserRequest $request) {
        $requestData = [
            'email' => $request->email,
            'full_name' => $request->fullName,
            'role_id' => $request->roleId,
            'company_id' => $request->companyId,
        ];
        if ($request->password) {
            $requestData['password'] =  bcrypt($request->password);
        }
        $data = User::create($requestData);
        return response()->json([
            'message' => 'User successfully registered',
            'success' => true
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
    */

    /**
        * @OA\Post(
        *      path="/api/auth/logout",
        *      operationId="Auth Logout",
        *      tags={"Authentication"},
        *      summary="User Logout",
        *      description="User Logout Here",
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="User Successfully Logined"),
        *      @OA\Response(response=401, description="Unauthenticated"),
        *      @OA\Response(response=403, description="Forbidden"),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        *     )
    */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
    */

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
