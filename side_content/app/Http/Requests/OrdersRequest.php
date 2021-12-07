<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdersRequest extends FormRequest
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
            'public_work_id' => 'required',
            'concepts_list.*.concept' => 'required',
            'concepts_list.*.measurement' => 'required',
            'concepts_list.*.quantity' => 'required',
            'concepts_list.*.purchase_price' => 'required'
        ];
    }
}
