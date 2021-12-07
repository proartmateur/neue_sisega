<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method()){
            case 'POST' :
                return [
                    'name' => 'required|max:255',
                    'email' => 'required|email|unique:users,email|email',
                    'password' => 'required|min:6',
                    'stall'=>'required',
                    'role'=>'required',
                    'status'=>'required',
                ];

                break;

            case 'PUT' :
                return [
                    'email' => 'nullable|email',
                    'password' => 'nullable|min:6',
                ];
                break;
        }
    }
}
