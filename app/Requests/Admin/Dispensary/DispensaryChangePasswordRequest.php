<?php

namespace App\Http\Requests\Admin\Dispensary;

use Illuminate\Foundation\Http\FormRequest;

class DispensaryChangePasswordRequest extends FormRequest
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
        return [
            'email' => 'required',
            'old_password' => 'required',
            'new_password' => 'required|min:8|different:old_password',
            'password_confirmation' => 'required|min:8|same:new_password',
        ];
    }
}
