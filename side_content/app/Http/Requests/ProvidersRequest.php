<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvidersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required',
            /*'name' => 'required',
            'surnames' => 'required',
            'company' => 'required',*/
            'bank' => 'required',
            'clabe' => 'required',
            'account' => 'required',
            /*'public_works_id' => 'required|array|min:1'*/
        ];
    }
}
