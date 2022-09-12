<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            ],
            'begin_academic_year' => [
                'nullable',
                'date',
                'before:end_academic_year',
            ],
            'end_academic_year' => [
                'nullable',
                'date',
                'after:begin_academic_year',
            ],
        ];

        return $rules;
    }
}
