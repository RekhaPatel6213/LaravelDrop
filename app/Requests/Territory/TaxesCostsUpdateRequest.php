<?php

namespace App\Http\Requests\Territory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Territory\TerritoryModule;

class TaxesCostsUpdateRequest extends FormRequest
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
            'territory_id' => 'required|numeric|exists:territories,id',
            'location_id' => [
                'required',
                Rule::exists('territory_modules', 'module_id')->where('module_type', TerritoryModule::LOCATION)
            ],
            'state_tax' => 'sometimes|numeric|max:100',
            'local_tax' => 'sometimes|numeric|max:100',
            'excise_tax' => 'sometimes|numeric|max:100',
            'cannabis_tax_medical' => 'sometimes|numeric|max:100',
            'cannabis_tax_adult' => 'sometimes|numeric|max:100',
            'minimum_order_cost' => 'sometimes|numeric|max:9999999',
            'delivery_fee' => 'sometimes|numeric|max:9999999',
            'cost_for_free_delivery' => 'sometimes|numeric|max:9999999',
        ];

        return $validationRules;
    }
}
