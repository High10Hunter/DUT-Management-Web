<?php

namespace App\Http\Requests\Module;

use Illuminate\Foundation\Http\FormRequest;

class StoreModuleRequest extends FormRequest
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
            'subject_id' => [
                'required',
            ],
            'lecturer_id' => [
                'required',
            ],
            'schedule' => [
                'required',
                'json',
            ],
            'start_slot' => [
                'required',
                'numeric',
                'lt:end_slot',
            ],
            'end_slot' => [
                'required',
                'numeric',
                'gt:start_slot',
            ],
            'begin_date' => [
                'required',
                'date',
            ],
            'lessons' => [
                'required',
                'numeric'
            ],
        ];

        return $rules;
    }
}
