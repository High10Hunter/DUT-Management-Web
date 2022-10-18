<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoleEnum;
use App\Models\_Class;
use App\Models\Faculty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreEAOStaffRequest extends FormRequest
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
        ];
        return $rules;
    }
}
