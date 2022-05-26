<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Hub\Product;

class ProductAddStockRequest extends FormRequest
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
        $request['productId'] = $this->route('productId');
        return $request;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'productId' => 'required|numeric|exists:products,id,dispensary_id,'.tenant('id'),
            'product_detail_id' => 'numeric|exists:product_details,id,product_id,'.$this->route('productId'),
            'stock' => 'required|numeric|gt:0'
        ];
    }

    public function messages()
    {
        return [
            'productId.exists' => __('message.notFound', ['name' => __('product.product')]),
            'product_detail_id.exists' => __('message.notFound', ['name' => __('product.productDetail')])
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $product = Product::find($this->route('productId'));
            
            if ($product && $product->is_unlimited === Product::YES) {
                $validator->errors()->add('product',__('product.productUnlimited'));
            }

            if ($product && $product->quantity_type === Product::PREPACKAGED && $this->product_detail_id === null) {
                $validator->errors()->add('product', __('validation.required', ['attribute' => __('product.productDetailId')]));
            }
        });
    }
}
