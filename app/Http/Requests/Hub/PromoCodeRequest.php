<?php

namespace App\Http\Requests\Hub;

use App\Models\Hub\PromoCode;
use Illuminate\Foundation\Http\FormRequest;

class PromoCodeRequest extends FormRequest
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
        $validationRules =  [
            'promo_code' => 'required|max:50',
            'applicable_to' => 'required',
            'discount_type' => 'required|in:'. PromoCode::PERCENTAGE .',' . PromoCode::FIXED,
            'discount_value' => 'required|numeric|max:999999',
            'applies_to' =>'required|in:'. PromoCode::ORDER .',' . PromoCode::PRODUCT,
            'product_id' => 'required_if:applies_to,'. PromoCode::PRODUCT .'|numeric|exists:products,id',
            'use_minimum' => 'required|in:'. PromoCode::NONE .',' . PromoCode::AMOUNT,
            'minimum_amount' => 'required_if:use_minimum,'. PromoCode::AMOUNT .'|numeric|max:999999',
            'unlimited' => 'required|boolean',
            'usage_limit' => 'required_if:unlimited,false|numeric|max:65535',
            'start_date_time' => 'required|date_format:Y-m-d H:i:s',
        ];

        if ($routeName == 'promo_code.patch') {
            foreach ($validationRules as $key => $validationRule) {
                $validationRules[$key] = "sometimes|{$validationRule}";
            }
            $validationRules['promoCodeId'] = 'required|numeric|exists:promo_codes,id';
        }
        $validationRules['end_date_time'] = 'sometimes|required|date_format:Y-m-d H:i:s';
        $validationRules['status'] = 'sometimes|required|in:' . PromoCode::ACTIVE . ',' . PromoCode::INACTIVE;

        if ($routeName == 'promo_code.update') {
            $validationRules['promoCodeId'] = 'required|numeric|exists:promo_codes,id';
        }

        if (in_array($routeName, ['promo_code.delete', 'promo_code.get'])) {
            $validationRules = [
                'promoCodeId' => 'required|numeric|exists:promo_codes,id'
            ];
        }

        return $validationRules;
    }


    protected function prepareForValidation()
    {
        $routeName = $this->route()->getName() ?? '';
        if (in_array($routeName, ['promo_code.delete', 'promo_code.get', 'promo_code.patch', 'promo_code.update'])) {
            $this->merge([
                'promoCodeId' => $this->route('promoCodeId'),
            ]);
        }
    }
}
