<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Hub\Reward;
use Illuminate\Validation\Rule;

class RewardCreateRequest extends FormRequest
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

    public function all($key = null)
    {
        $request = parent::all($key);
        if($this->route('rewardId')){
            $request['rewardId'] = $this->route('rewardId');
        }
        return $request;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationRules = [
            'rewardId' => 'sometimes|numeric|exists:rewards,id,dispensary_id,'.tenant('id'),
            'name' => 'required|min:2|max:255|unique:rewards,name,NULL,id,dispensary_id,'.tenant('id'),
            'points' => ['required',Rule::in(Reward::POINTS)],
            'discount_type' => 'required_if:is_inventory,'.Reward::YES.'|nullable',
            'discount_price' => 'required_if:is_inventory,'.Reward::YES.'|nullable|numeric',
            'product_id' => 'required_if:is_inventory,'.Reward::YES.'|nullable|numeric',
            'product_detail_id' => 'required_if:is_inventory,'.Reward::YES.'|nullable|numeric',
            'description' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        /**
         * if method is patch or single field request update than add `sometimes`
         * as a first validation rule to check.
         */
        if ($this->isMethod('PATCH')) {
            foreach ($validationRules as $key => $validationRule) {
                $validationRules[$key] = "sometimes|{$validationRule}";
            }
        }
        return $validationRules;

    }
}
