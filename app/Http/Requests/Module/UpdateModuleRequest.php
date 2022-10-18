<?php

namespace App\Http\Requests\Module;

use Illuminate\Foundation\Http\FormRequest;

class UpdateModuleRequest extends FormRequest
{
    public function authorize()
    {
        return isAdmin() || isEAOStaff();
    }

    public function rules()
    {
        $rules = [
            'lecturer_id' => [
                'required',
            ],
            'schedule' => [
                'required',
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
