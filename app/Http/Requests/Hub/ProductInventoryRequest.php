<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Hub\ProductInventory;
use App\Models\Hub\Product;
use App\Models\Hub\Inventory;
use App\Http\Traits\ServiceTrait;

class ProductInventoryRequest extends FormRequest
{
    use ServiceTrait;

    protected $inventoryFeature, $modelType, $inventoryModelType;

    public function __construct()
    {
        $this->inventoryFeature = $this->getInventoryAccess();
        $this->modelType = $this->getProductInventoryModelType();
        $this->inventoryModelType = $this->getInventoryModelType();
    }

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
        $table = app(ProductInventory::MODELCLASS[$this->model_type])->getTable();

        return [
            'productId' => 'required|numeric|exists:products,id,dispensary_id,'.tenant('id'),
            'product_detail_id' => 'sometimes|required|numeric|exists:product_details,id,product_id,'.$this->route('productId'),
            'stock' => 'required|numeric|gt:0',
            'model_type' => ['required', Rule::in([$this->modelType])],
            'model_ids' => 'required_with:model_type|array',
            'model_ids.*' => 'numeric|exists:'.$table.',id,dispensary_id,'.tenant('id'),
        ];
    }

    public function messages()
    {
        return [
            'productId.exists' => __('message.notFound', ['name' => __('product.product')]),
            'product_detail_id.exists' => __('message.notFound', ['name' => __('product.productDetail')]),
            'model_ids.required' => __('validation.required', ['attribute' => $this->model_type]),
            'model_type.in' => __('validation.exists', ['attribute' => __('product.modelType')]).__('product.itShouldBe', ['name' => $this->modelType]),
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

            if ($this->model_ids && $this->inventoryFeature) {
                $inventories = Inventory::hasModel($this->inventoryModelType)->inIds($this->model_ids)->pluck('id')->toArray();
                $invalidModelIds = array_diff($this->model_ids, $inventories);
                if($invalidModelIds){
                    $validator->errors()->add('model_ids', __('validation.exists', ['attribute' => __('product.inventory') .' '. implode(', ', $invalidModelIds)]));
                }
            }
        });
    }
}
