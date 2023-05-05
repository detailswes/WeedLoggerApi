<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Installation;
use Illuminate\Support\Facades\Auth;

class InstallationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
        * @OA\Get(
        *      path="/api/installations",
        *      operationId="List of all Installations",
        *      tags={"Installation"},
        *      summary="List of all Installations",
        *      description="Get list of all Installations here",
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="List of all Installations retrieved successfully."),
        *      @OA\Response(response=403, description="You are not authorized."),
        *     )
    */
    public function index()
    {
        if(Auth::user()->can('installation_listing')){
            $installation = Installation::with('company')->orderBy('id','DESC')->get();
             return response()->json([
                 'success' => true,
                 'message' => "List of all Installations retrieved successfully.",
                 'data' => $installation,
             ]);
         }
         abort(403, 'You are not authorized.');
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
        * @OA\Post(
        *      path="/api/installations",
        *      operationId="Add installations",
        *      tags={"Installation"},
        *      summary="Add New Installation",
        *      description="Add new installation here",
        *      @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name", "companyId"},
        *               @OA\Property(property="name", type="string"),
        *               @OA\Property(property="companyId", type="integer"),
        *            ),
        *        ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="Installation Register Successfully !!"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *     )
    */
    public function store(Request $request)
    {
        if(Auth::user()->can('installation_create')){
            $requestData = [
                'name' => $request->name,
                'company_id' => $request->companyId,
            ];
            $data = Installation::updateOrCreate([
                'id' => $request->id
            ], $requestData);

            return response()->json([
                'success' => true,
                'message' => 'Installation Register Successfully !!',
                'data' => $data,
            ]);
        }
        abort(403, 'You are not authorized.');
    }

    /**
     * Display the specified resource.
     */

    /**
        * @OA\Get(
        *      path="/api/installations/{id}",
        *      operationId="Show Installations By Id",
        *      tags={"Installation"},
        *      summary="Show Installation By Id",
        *      description="Show installation by id",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Show Installations by id",
        *         required=true,
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="Installation details retrieved successfully."),
        *      @OA\Response(response=403, description="You are not authorized."),
        *     )
    */
    public function show(string $id)
    {
        if(Auth::user()->can('installation_edit')){
            $data = Installation::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Installation details retrieved successfully.',
                'data' => $data,
            ]);
        }
        abort(403, 'You are not authorized.');
    }

    /**
     * Update the specified resource in storage.
     */

    /**
        * @OA\Put(
        *      path="/api/installations/{id}",
        *      operationId="updateInstallation",
        *      tags={"Installation"},
        *      summary="Update a installation's details by ID",
        *      description="Update a installation's details by ID",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the installation to update",
        *         required=true,
        *         @OA\Schema(
        *               type="integer",
        *         ),
        *      ),
        *      @OA\RequestBody(
        *         @OA\MediaType(
        *            mediaType="application/json",
        *            @OA\Schema(
        *               required={"name", "companyId"},
        *               @OA\Property(property="name", type="string"),
        *               @OA\Property(property="companyId", type="integer"),
        *            ),
        *        ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="Installation Updated Successfully"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *     )
    */
    public function update(Request $request, string $id)
    {
        if(Auth::user()->can('installation_edit')){
            $requestData = [
                'name' => $request->name,
                'company_id' => $request->companyId,
            ];
            $data = Installation::find($id);
            $data->update($requestData);
            return response()->json([
                'success' => true,
                'message' => 'Installation update successfully.',
                'data' => $data,
            ]);
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
}
