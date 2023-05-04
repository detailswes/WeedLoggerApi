<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
        * @OA\Get(
        *      path="/api/roles",
        *      operationId="Roles Listing",
        *      tags={"Roles"},
        *      summary="All Roles Listing",
        *      description="All roles Listing Here",
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="All Roles List !!"),
        *      @OA\Response(response=401, description="Unauthenticated"),
        *      @OA\Response(response=403, description="Forbidden"),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        *     )
    */
    public function index()
    {
        if (Auth::user()->can('roles_listing')) {
            $roles = Role::all();
            return response()->json([
                'success' => true,
                'message' => "All Roles List !!",
                'data' => $roles,
            ]);
        }
        abort(403, 'You are not authorized.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
