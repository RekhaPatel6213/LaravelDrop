<?php

namespace App\Http\Requests\Admin\Dispensary;

use Illuminate\Foundation\Http\FormRequest;

class DispensaryPaymentMethodRequest extends FormRequest
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
        $validationRules = [
            'payment_methods.*.id' => 'required|numeric|exists:dispensary_payment_methods',
            'payment_methods.*.payment_title' => 'required|max:50',
            'payment_methods.*.description' => 'nullable|sometimes|max:50',
            'payment_methods.*.status' => 'sometimes|in:ACTIVE,INACTIVE',
            'payment_methods.*.enable_tip' => 'sometimes|in:YES,NO',
            'payment_methods.*.enable_cash' => 'sometimes|in:YES,NO',
        ];

        return $validationRules;
    }
}
