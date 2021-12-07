<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollsRequest extends FormRequest
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
            'days_worked' => 'required_if:employee_type,==,1',
            'hours_worked' => 'required_if:employee_type,==,1',
            'total_amount' => 'required_if:employee_type,==,2',
            'date' => 'required',
            'public_work_id' => 'required'
            /*'concepts_list.*.bonus_id' => 'required',
            'concepts_list.*.bonus_date' => 'required'*/
        ];
    }
}
