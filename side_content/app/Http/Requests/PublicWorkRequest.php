<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicWorkRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required',
                    /*'budget' => 'required',*/
                    'status' => 'required',
                    'supervisors' => 'required|array|min:1'
                ];

                break;
            
            case 'PUT':
                return [
                    // 'budget' => 'numeric',
                    'status' => 'required',
                    'supervisors' => 'required|array|min:1'
                ];
                break;
        }
    }
}
