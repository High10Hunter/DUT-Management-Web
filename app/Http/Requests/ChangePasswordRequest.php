<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isAdmin() || isEAOStaff() || isLecturer() || isStudent();
    }

    public function rules()
    {
        $rules = [
            'user_id' => [
                'required',
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ];

        return $rules;
    }
}
