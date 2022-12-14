<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoleEnum;
use App\Models\_Class;
use App\Models\Faculty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isAdmin();
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
                'nullable',
            ],
            'phone_number' => [
                'string',
                'nullable',
                'min:10',
                'max:20',
            ],
            'gender' => [
                'required',
            ],
            'avatar' => [
                'nullable',
                'image',
            ],
            'birthday' => [
                'required',
                'date',
            ],
            'role' => [
                'required',
                Rule::in(UserRoleEnum::getValues()),
            ],
            'faculty_id' => [
                'nullable',
                Rule::in(Faculty::pluck('id')->toArray()),
            ],
            'class_id' => [
                'nullable',
                Rule::in(_Class::pluck('id')->toArray()),
            ]
        ];
        return $rules;
    }
}
