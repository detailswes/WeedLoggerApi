<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     if (auth()->user()->can('user_create') || auth()->user()->can('user_edit')) {
    //         return true;
    //     }

    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'full_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->id,
            'role_id' => 'required|exists:App\Models\Role,id',
            'company_id' => 'required|exists:App\Models\Company,id',
            'password' => [
                'required',
                'min:6',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
            ],

        ];
        

        // if (!$this->id) {
        //     $rules['password'] = 'required';
        // }

        return $rules;
    }
}
