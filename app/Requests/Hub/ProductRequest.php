<?php

namespace App\Http\Requests\Hub;

use App\Http\Traits\DispensaryTrait;
use App\Models\Hub\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    use DispensaryTrait;

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
        $productId = isset($this->productId) ? $this->productId : null;

        $validationRules = [
            'brand' => 'nullable|min:2|max:100',
            'category_id' => 'required|numeric',
            'thc' => 'nullable|numeric|between:0,99.99',
            'thc_type' => 'required_with:thc',
            'cbd' => 'nullable|numeric|between:0,99.99',
            'cbd_type' => 'required_with:cbd',
            'cbn' => 'nullable|numeric|between:0,99.99',
            'cbn_type' => 'required_with:cbn',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity_type' => ['required', Rule::in(Product::QUANTITY_TYPES)],
            'is_unlimited' => [Rule::in([Product::YES, Product::NO])],
        ];

        if ($this->product_details !== null) {
            if ('array' !== gettype($this->product_details)) {
                $this->product_details = $this->stringToArray($this->product_details);
            }

            $required = 'required|';
            $productId = isset($this->productId) ? $this->productId : null;
            if ($productId) {
                $required = 'sometimes|';
            }

            $quantityType = isset($this->quantity_type) ? $this->quantity_type : null;
            if ($quantityType === Product::GRAMS) {
                foreach ($this->product_details as $key => $value) {
                    $validationRules['product_details.'.$key.'.variant_id'] = $required.'numeric';
                    $validationRules['product_details.'.$key.'.price'] = $required.'numeric';
                }
                $validationRules['stock'] = 'required_if:is_unlimited,'.Product::NO.'|nullable|numeric';
                $validationRules['wholesale_price'] = 'nullable|numeric';
            } elseif ($quantityType === Product::PREPACKAGED) {
                foreach ($this->product_details as $key => $value) {
                    $validationRules['product_details.'.$key.'.variant_id'] = $required.'numeric';
                    $validationRules['product_details.'.$key.'.price'] = $required.'numeric';
                    $validationRules['product_details.'.$key.'.wholesale_price'] = 'nullable|numeric';
                    $validationRules['product_details.'.$key.'.stock'] = 'sometimes|required_if:is_unlimited,'.Product::NO.'|nullable|numeric';
                }
            } elseif ($quantityType === Product::UNITS) {
                $validationRules['price'] = 'required|numeric';
                $validationRules['stock'] = 'required_if:is_unlimited,'.Product::NO.'|nullable|numeric';
                $validationRules['wholesale_price'] = 'nullable|numeric';
            }
        }

        if ($this->isMethod('POST')) {
            $validationRules['name'] = 'sometimes|nullable|min:2|max:255|unique:products,name,'.$productId.',id,dispensary_id,'.tenant('id');
            $validationRules['category_id'] = 'sometimes|nullable|numeric';
            $validationRules['quantity_type'] = ['sometimes', Rule::in(Product::QUANTITY_TYPES)];
        }

        /*
         * if method is patch or single field request update than add `sometimes`
         * as a first validation rule to check.
         */
        if ($this->isMethod('PATCH')) {
            foreach ($validationRules as $key => $validationRule) {
                if ($key === 'quantity_type') {
                    $validationRules[$key] = ['sometimes', Rule::in(Product::QUANTITY_TYPES)];
                } elseif ($key !== 'quantity_type') {
                    $validationRules[$key] = "sometimes|{$validationRule}";
                }
            }
        }

        return $validationRules;
    }

    public function messages()
    {
        $messages = [];

        if ($this->product_details !== null) {
            if ('array' !== gettype($this->product_details)) {
                $this->product_details = $this->stringToArray($this->product_details);
            }

            $quantityType = isset($this->quantity_type) ? $this->quantity_type : null;
            if ($quantityType === Product::GRAMS) {
                foreach ($this->product_details as $key => $value) {
                    $messages['product_details.'.$key.'.variant_id.required'] = $this->customeMessage('variant_id', $key);
                    $messages['product_details.'.$key.'.variant_id.numeric'] = $this->customeMessage('variant_id', $key, 'numeric');
                    $messages['product_details.'.$key.'.price.required'] = $this->customeMessage('price', $key);
                    $messages['product_details.'.$key.'.price.numeric'] = $this->customeMessage('price', $key, 'numeric');
                }
            } elseif ($quantityType === Product::PREPACKAGED) {
                foreach ($this->product_details as $key => $value) {
                    $messages['product_details.'.$key.'.variant_id.required'] = $this->customeMessage('variant_id', $key);
                    $messages['product_details.'.$key.'.variant_id.numeric'] = $this->customeMessage('variant_id', $key, 'numeric');
                    $messages['product_details.'.$key.'.price.required'] = $this->customeMessage('price', $key);
                    $messages['product_details.'.$key.'.price.numeric'] = $this->customeMessage('price', $key, 'numeric');
                    $messages['product_details.'.$key.'.wholesale_price.required'] = $this->customeMessage('wholesale_price', $key);
                    $messages['product_details.'.$key.'.wholesale_price.numeric'] = $this->customeMessage('wholesale_price', $key, 'numeric');
                    $messages['product_details.'.$key.'.stock.required_if'] = $this->customeMessage('stock', $key, 'required_if');
                    $messages['product_details.'.$key.'.stock.numeric'] = $this->customeMessage('stock', $key, 'numeric');
                }
            }
        }

        return $messages;
    }

    private function customeMessage($field, $key, $rule = 'required')
    {
        $message = '';
        switch ($rule) {
            case 'numeric':
                $message = 'must be a number';
                break;
            case 'required_if':
                $message = 'field is required when is_unlimited is NO';
                break;
            default:
                $message = 'field is required';
                break;
        }

        return 'The '.str_replace('_', ' ', $field).' of product price detail key '.($key + 1)." $message.";
    }
}
