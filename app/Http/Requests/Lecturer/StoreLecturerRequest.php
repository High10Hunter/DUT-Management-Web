<?php

namespace App\Http\Requests\Lecturer;

use Illuminate\Foundation\Http\FormRequest;

class StoreLecturerRequest extends FormRequest
{
    public function authorize()
    {
        return isAdmin() || isEAOStaff();
    }

    public function rules()
    {
        $rules = [
            'name' => [
                'required',
                'string'
            ],
            'birthday' => [
                'required',
                'date',
            ],
            'gender' => [
                'required',
                'boolean',
            ],
            'email' => [
                'required',
                'email',
            ],
            'faculty_id' => [
                'required',
            ],
            'status' => []
        ];

        return $rules;
    }
}
