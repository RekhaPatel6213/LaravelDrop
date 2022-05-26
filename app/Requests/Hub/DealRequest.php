<?php

namespace App\Http\Requests\Hub;

use App\Models\Hub\Deal;
use Illuminate\Foundation\Http\FormRequest;

class DealRequest extends FormRequest
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
            'name' => 'required|max:255',
            'deal_type' => 'required|in:'. Deal::NORMAL . ',' . Deal::BUY_X . ',' . Deal::SPEND_X,
            'discount_type' => 'required|in:'. Deal::FREE . ',' . Deal::AMOUNT . ',' . Deal::PERCENT . ',' . Deal::FIXED,
            'applied_on' => 'required|in:'. Deal::CART . ',' . Deal::PRODUCT . ',' . Deal::CATEGORY . ',' . Deal::BRAND. ',' . Deal::TOTAL,
            'discount_value' => 'required|numeric|max:999999',
            'total_usage_limit' => 'required|numeric',
            'per_user_limit' => 'required|numeric',
            'min_spend' => 'required|numeric',
            'max_spend' => 'required|numeric',
            'active_days' => [
                'required',
                function ($attribute, $value, $fail) {
                    $daysArr = [0,1,2,3,4,5,6];
                    foreach ($value as $item) {
                        if (!in_array($item, $daysArr)) {
                            $fail('The '.$attribute.' for value ' . $item . ' is invalid.');
                        }
                    }
                },
            ],

            'start_date' => 'required|date_format:"Y-m-d"',
        ];

        if ($routeName == 'deals.patch') {
            foreach ($validationRules as $key => $validationRule) {
                if (is_array($validationRule)) {
                    array_unshift($validationRule ,'sometimes');
                    $validationRules[$key] = $validationRule;
                    continue;
                }
                $validationRules[$key] = "sometimes|{$validationRule}";
            }
            $validationRules['dealId'] = 'required|numeric|exists:deals,id,dispensary_id,' . tenant('id');
        }

        $optionalRules = [
            'description' => 'sometimes|required',
            'condition_on' => 'required_if:deal_type,in:'. Deal::SPEND_X . ','. Deal::BUY_X . '|in:'. Deal::CART . ',' . Deal::PRODUCT . ',' . Deal::CART . ',' . Deal::BRAND,
            'start_time' => 'sometimes|required|date_format: "H:i"',
            'end_time' => 'sometimes|required|date_format: "H:i"',
            'end_date' => 'sometimes|required|date_format:"Y-m-d"',
            'applied_products' => 'required_if:applied_on,' . Deal::PRODUCT . '|array',
            'applied_products.*' => 'required|numeric|exists:products,id,dispensary_id,' . tenant('id'),
            'applied_variants' => 'sometimes|required|array',
            'applied_variants.*' => 'required|numeric|exists:product_variants',
            'applied_categories' => 'required_if:applied_on,' . Deal::CATEGORY . '|array',
            'applied_categories.*' => 'required|numeric|exists:taxons,id',
            'applied_brands' => 'required_if:applied_on,' . Deal::BRAND . '|array',
            'applied_brands.*' => 'required|numeric|exists:brands,id,dispensary_id,' . tenant('id'),
            'exclude_products' => 'sometimes|required|array',
            'exclude_products.*' => 'required|numeric|exists:products,id,dispensary_id,' . tenant('id'),
            'exclude_categories' => 'sometimes|required|array',
            'exclude_categories.*' => 'required|numeric|exists:taxons,id',
            'exclude_brands' => 'sometimes|required|array',
            'exclude_brands.*' => 'required|numeric|exists:brands,id,dispensary_id,' . tenant('id'),
            'condition_products' => 'required_if:condition_on,' . Deal::PRODUCT . '|array',
            'condition_products.*' => 'required|numeric|exists:products,id,dispensary_id,' . tenant('id'),
            'condition_brands' => 'required_if:condition_on,' . Deal::BRAND . '|array',
            'condition_brands.*' => 'required|numeric|exists:brands,id,dispensary_id,' . tenant('id'),
        ];

        if ($routeName == 'deals.update') {
            $validationRules['dealId'] = 'required|numeric|exists:deals,id,dispensary_id,'. tenant('id');
        }

        if (in_array($routeName, ['deals.delete', 'deals.list', 'deals.get'])) {
            $validationRules = [
                'dealId' => 'required|numeric|exists:deals,id,dispensary_id,'. tenant('id')
            ];
        }
        $validationRules = array_merge($validationRules, $optionalRules);
        return $validationRules;
    }


    protected function prepareForValidation()
    {
        $routeName = $this->route()->getName() ?? '';
        if (in_array($routeName, ['deals.delete', 'deals.patch', 'deals.update', 'deals.get'])) {
            $this->merge([
                'dealId' => $this->route('dealId'),
            ]);
        }
    }
}
