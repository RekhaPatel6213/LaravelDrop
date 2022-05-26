<?php

namespace App\Http\Requests\Admin\Dispensary;

use Illuminate\Foundation\Http\FormRequest;

class DispensaryCustomFeeRequest extends FormRequest
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
        $routeName = $this->route()->getName() ?? '';

        $validationRules = [
            'custom_fees.*.id' => 'required|numeric|exists:dispensary_custom_fees,id,dispensary_id,' . tenant('id'),
            'custom_fees.*.title' => 'required|max:50',
            'custom_fees.*.description' => 'required',
            'custom_fees.*.fee_amount' => 'required|numeric',
        ];

        if (in_array($routeName, ['order_fees.delete'])) {
            $validationRules = [
                'orderFeeId' => 'required|numeric|exists:dispensary_custom_fees,id,dispensary_id,' . tenant('id')
            ];
        }

        return $validationRules;
    }

    protected function prepareForValidation()
    {
        $routeName = $this->route()->getName() ?? '';
        if (in_array($routeName, ['order_fees.delete'])) {
            $this->merge([
                'orderFeeId' => $this->route('orderFeeId'),
            ]);
        }
    }
}
