<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
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

    public function rules()
    {
        $rules = [
            'module_id' => [
                'required',
            ],
            'date' => [
                'required',
                'date',
            ],
            'type' => [
                'required',
                'integer',
            ],
            'start_slot' => [
                'required',
                'integer',
            ],
            'proctor_id' => [
                'required',
                'integer',
            ]
        ];

        return $rules;
    }
}
