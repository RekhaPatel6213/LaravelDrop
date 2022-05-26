<?php

namespace App\Http\Requests\Admin\Dispensary;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPriceRequest extends FormRequest
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
            'name' => 'required|max:100',
            'amount' => 'required|numeric',
            'type' => 'required|in:SUBSCRIPTION,SMS',
            'recurring' => 'required|in:YES,NO',
            'interval' => 'required_if:recurring,YES|in:DAY,WEEK,MONTH,YEAR',
            'months' => 'required_if:recurring,YES|integer',
            'sms' => 'sometimes|integer'
        ];
    }
}
