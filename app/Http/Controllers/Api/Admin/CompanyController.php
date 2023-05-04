<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
    */

    /**
        * @OA\Get(
        *      path="/api/companies",
        *      operationId="List of all companies",
        *      tags={"Company"},
        *      summary="List of all companies",
        *      description="Get list of all companies here",
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="All Companies"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *      @OA\Response(response=422, description="Bad request"),
        *     )
    */
    public function index()
    {
        if(Auth::user()->can('company_listing')){
           $companies = Company::all();
            return response()->json([
                'success' => true,
                'message' => "All Companies",
                'data' => $companies,
            ]);
        }
        abort(403, 'You are not authorized.');
    }

    /**
     * Store a newly created resource in storage.
    */

    /**
        * @OA\Post(
        *      path="/api/companies",
        *      operationId="Add Company",
        *      tags={"Company"},
        *      summary="Add New Company Name",
        *      description="Add new company name here",
        *      @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name"},
        *               @OA\Property(property="name", type="text"),
        *            ),
        *        ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="Company Register Successfully !!"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *      @OA\Response(response=422, description="Bad request"),
        *     )
    */
    public function store(Request $request)
    {
        if(Auth::user()->can('company_create')){
            $requestData = [
                'name' => $request->name,
            ];
            $data = Company::updateOrCreate([
                'id' => $request->id
            ], $requestData);

            return response()->json([
                'success' => true,
                'message' => 'Company Register Successfully !!',
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
        *      path="/api/companies/{id}",
        *      operationId="Show Company By Id",
        *      tags={"Company"},
        *      summary="Show Company By Id",
        *      description="Show company by id",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Show company by id",
        *         required=true,
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="Company Updated Successfully"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *      @OA\Response(response=422, description="Bad request"),
        *     )
    */
    public function show(string $id)
    {
        if(Auth::user()->can('company_edit')){
            $data = Company::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Company show successfully with this id.',
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
        *      path="/api/companies/{id}",
        *      operationId="Update Company By Id",
        *      tags={"Company"},
        *      summary="Update Company Name By Id",
        *      description="Update company name here using id",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Update company name by id",
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
        *               required={"name"},
        *               @OA\Property(property="name", type="string"),
        *            ),
        *        ),
        *      ),
        *      security={{"bearer":{}}},
        *      @OA\Response(response=200, description="Company Updated Successfully"),
        *      @OA\Response(response=403, description="You are not authorized."),
        *      @OA\Response(response=422, description="Bad request"),
        *     )
    */
    public function update(Request $request, string $id)
    {
        if(Auth::user()->can('company_edit')){
            $requestData = [
                'name' => $request->name,
            ];
            $data = Company::find($id);
            $data->update($requestData);
            return response()->json([
                'success' => true,
                'message' => 'Company update successfully.',
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
