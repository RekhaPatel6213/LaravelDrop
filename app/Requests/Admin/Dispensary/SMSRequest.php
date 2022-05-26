<?php

namespace App\Http\Requests\Admin\Dispensary;

use Illuminate\Foundation\Http\FormRequest;

class SMSRequest extends FormRequest
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
            'dispensary_id' => 'required|numeric',
            'plan_id' => 'sometimes|required|numeric',
            'sms' => 'sometimes|required|numeric',
            'sms_schedule' => 'sometimes|required',
        ];
    }
}
