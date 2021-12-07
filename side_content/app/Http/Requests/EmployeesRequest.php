<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeesRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'photography' => 'required_if:type,==,1|max:2000',
                    'name' => 'required',
                    /*'last_name' => 'required',*/
                    'birthdate' => "required_if:type,==,1",
                    'type' => 'required',
                    'cell_phone' => "required_if:type,==,1",
                    'direction' => "required_if:type,==,1",

                    /*'imss_number' => ['required_if:type,==,1', 'required_without:imss'],*/
                    'imss_number' => ['required_without:imss'],

                    'curp' => 'required_if:type,==,1|size:18|alpha_num',
                    'rfc' => 'required_if:type,==,1|alpha_num|between:12,13',
                    /*'aptitudes' => 'required',*/
                    'stall' => 'required',
                    'salary_week' => 'required_if:type,==,1',
                    /*'public_works_id' => 'required',*/
                    'registration_date' => 'required_if:type,==,1',
                    /*'phone' => 'required|Integer|digits: 10',*/
                    'status' => 'required',
                    // 'bank' => 'required',
                    // 'clabe' => 'required',
                    // 'account' => 'required'
                ];

                break;
            
            case 'PUT':
                return [
                    'cell_phone' => 'required_if:type,==,1|Integer|digits: 10',
                    'imss_number' => ['required_without:imss'],
                    'curp' => 'required_if:type,==,1|max:18|alpha_num',
                    'rfc' => 'required_if:type,==,1|max:13|alpha_num',
                    /*'phone' => 'Integer|digits: 10',*/
                    'status' => 'required',
                    // 'bank' => 'required_if:type,==,1',
                    // 'clabe' => 'required_if:type,==,1',
                    // 'account' => 'required_if:type,==,1'
                ];
                break;
        }
    }
}
