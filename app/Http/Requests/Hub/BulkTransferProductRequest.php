<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Traits\ServiceTrait;
use App\Http\Traits\ProductInventoryTrait;
use App\Models\Hub\Inventory;
use App\Models\Hub\Product;

class BulkTransferProductRequest extends FormRequest
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
                'from_inventory_id' => 'required|numeric',
                'to_inventory_id' => ['required','numeric', Rule::notIn([$this->fromInventoryId])]
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
                $inventoryIds = $this->from_inventory_id > 0 ? [$this->from_inventory_id, $this->to_inventory_id] : [$this->to_inventory_id];
                $inventories = Inventory::inIds($inventoryIds)->hasModel($this->inventoryModelType)->pluck('id')->toArray();
                $invalidIds = array_diff($inventoryIds, $inventories);
                if ($invalidIds) {
                    $validator->errors()->add('to_inventory_id', __('validation.exists', ['attribute' => __('product.inventory') .' '. implode(', ', $invalidIds)]));
                }
            }
        });
    }
}
