<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WeedmapsRequest extends FormRequest
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
            'wm_client_id' => 'required',
            'wm_client_secret' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'wm_client_id.required' => 'The Weedmap Client Id field is required.',
            'wm_client_secret.required' => 'The Weedmap Client Secret field is required.'
        ];
    }
}
