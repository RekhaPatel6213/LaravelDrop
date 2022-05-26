<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class LoginRequest extends FormRequest
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
        $user = 'admin_users';
        if(Route::currentRouteName() === 'dispensary.login'){
            $user = 'dispensary_users';
        }

        return [
            'email' => "required|email|exists:".$user.",email,deleted_at,NULL",
            'password' => 'required|string|min:6'
        ];
    }
}
