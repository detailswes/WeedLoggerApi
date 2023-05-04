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
        *      @OA\Response(response=200, description="All Installations"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *     )
    */
    public function index()
    {
        if(Auth::user()->can('installation_listing')){
            $installation = Installation::all();
             return response()->json([
                 'success' => true,
                 'message' => "All Installation",
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
        *               @OA\Property(property="name", type="text"),
        *               @OA\Property(property="companyId", type="integer"),
        *            ),
        *        ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="Installation Register Successfully !!"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *      @OA\Response(response=422, description="Bad request"),
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
        *      @OA\Response(response=200, description="Installations  show successfully with this id."),
        *      @OA\Response(response=403, description="You are not authorized."),
        *      @OA\Response(response=422, description="Bad request"),
        *     )
    */
    public function show(string $id)
    {
        if(Auth::user()->can('installation_edit')){
            $data = Installation::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Installations show successfully with this id.',
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
        *      operationId="Update Installation By Id",
        *      tags={"Installation"},
        *      summary="Update Installation By Id",
        *      description="Update installation here using id",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Update installation by id",
        *         required=true,
        *         @OA\Schema(
        *               type="integer",
        *         ),
        *      ),
        *      @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name", "companyId"},
        *               @OA\Property(property="name", type="text"),
        *               @OA\Property(property="companyId", type="integer"),
        *            ),
        *        ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="Installation Updated Successfully"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *      @OA\Response(response=422, description="Bad request"),
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
