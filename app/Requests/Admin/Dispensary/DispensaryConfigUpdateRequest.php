<?php

namespace App\Http\Requests\Admin\Dispensary;

use Illuminate\Foundation\Http\FormRequest;

class DispensaryConfigUpdateRequest extends FormRequest
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
            'min_stock_alert' => 'sometimes|in:YES,NO',
            'min_gram_qty' => 'sometimes|numeric',
            'min_unit_qty' => 'sometimes|numeric',
        ];


        return $validationRules;
    }
}
