<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Traits\ServiceTrait;
use App\Http\Traits\ProductInventoryTrait;
use App\Models\Hub\Inventory;
use App\Models\Hub\Product;
use App\Rules\Hub\CustomInventoryRule;

class BulkTransferRequest extends FormRequest
{
    use ServiceTrait, ProductInventoryTrait;

    protected $inventoryAccess, $inventoryModelType;

    public function __construct()
    {
        $this->inventoryAccess = $this->getInventoryAccess();
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validation = [];

        if ($this->inventoryAccess) { 
            $validation = [
                'from_inventory_id' => ['required', new CustomInventoryRule],
                'to_inventory_id' => ['required', Rule::notIn([$this->from_inventory_id]), new CustomInventoryRule],
                'products' => 'required',
                'products.*.product_id' => 'required|exists:products,id,dispensary_id,'.tenant('id'),
                'products.*.product_details.*.product_detail_id' => 'nullable|sometimes|required|exists:product_details,id',
                'products.*.product_details.*.stock' => 'required|numeric|gt:0',
            ];
        }

        return $validation;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->inventoryAccess) {
                $validator->errors()->add('inventoryFeature', __('message.disabledSetting', ['name' => __('product.inventoryFeature')]));
            }

            if ($this->inventoryAccess) {
                foreach ($this->products as $product) {

                    $productData = Product::find($product['product_id']);
                    if($productData){
                        foreach ($product['product_details'] as $detail) {

                            $productDetailId = $productData->quantity_type === Product::PREPACKAGED ? ($detail['product_detail_id'] ?? null) : null;

                            $checkStock = $this->checkBulkTransferStock($productData, $productDetailId ?? null, $detail['stock'], $this->from_inventory_id, true);
                            if(!$checkStock){
                                $validator->errors()->add('to_inventory_id', __('product.stockNotSufficient'));
                                break;
                            }
                        }
                    }
                }
            }
        });
    }
}
