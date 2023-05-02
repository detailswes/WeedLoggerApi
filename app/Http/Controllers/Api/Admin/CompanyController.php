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
                'message' => "Company Register Successfully !!",
                'data' => $data,
            ]);
        }
        abort(403, 'You are not authorized.');
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
