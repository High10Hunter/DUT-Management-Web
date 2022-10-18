<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isAdmin() || isEAOStaff();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:1',
                'max:50'
            ],
            'email' => [
                'email',
                'required',
            ],
            'phone_number' => [
                'string',
                'required',
                'min:10',
                'max:20',
            ],
            'gender' => [
                'required',
            ],
            'birthday' => [
                'required',
                'date',
            ],
            'status' => [
                'required',
            ],
        ];

        return $rules;
    }
}
