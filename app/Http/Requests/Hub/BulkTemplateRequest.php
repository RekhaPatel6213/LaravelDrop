<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Hub\CustomInventoryRule;
use Illuminate\Validation\Rule;

class BulkTemplateRequest extends FormRequest
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
        if($this->route('templateId')){
            $request['templateId'] = $this->route('templateId');
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
        $templateId = $this->templateId ?? null;

        $validationRules = [
            'templateId' => 'sometimes|numeric|exists:bulk_templates,id,dispensary_id,'.tenant('id'),
            'name' => 'required|max:100|unique:bulk_templates,name,'.$templateId.',id,dispensary_id,'.tenant('id'),
            'from_inventory_id' => ['required', new CustomInventoryRule],
            'to_inventory_id' => ['required', Rule::notIn([$this->from_inventory_id]), new CustomInventoryRule],
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id,dispensary_id,'.tenant('id'),
            'products.*.product_details.*.product_detail_id' => 'nullable|sometimes|required|exists:product_details,id',
        ];


        /**
         * if method is patch or single field request update than add `sometimes`
         * as a first validation rule to check.
         */
        if ($this->isMethod('PATCH')) {
            foreach ($validationRules as $key => $validationRule) {
                if (gettype($validationRule) !== 'array') {
                    $validationRules[$key] = strpos($validationRule, 'sometimes') === false ? "sometimes|{$validationRule}" : "{$validationRule}";
                } 
                if (gettype($validationRule) === 'array') {
                    $validationRules[$key] = array_merge($validationRule, ['sometimes']);
                }
            }
        }

        if ($this->isMethod('DELETE')) {
            $validationRules = [
                'templateId' => 'sometimes|numeric|exists:bulk_templates,id,dispensary_id,'.tenant('id'),
            ];
        }

        return $validationRules;
    }
}
