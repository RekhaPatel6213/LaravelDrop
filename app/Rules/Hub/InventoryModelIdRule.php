<?php

namespace App\Rules\Hub;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Traits\ServiceTrait;
use App\Models\Hub\Inventory;
use App\Models\Hub\ProductInventory;

class InventoryModelIdRule implements Rule
{
    use ServiceTrait;

    protected $modelType, $inventoryModelType;

    public function __construct($modelType)
    {
        $this->modelType = $modelType;
        $this->inventoryModelType = $this->getInventoryModelType();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $class = ProductInventory::MODELCLASS[$this->modelType];
        $flag = false;
        
        switch ($this->modelType) {
            case ProductInventory::CATEGORY :
            case ProductInventory::TERRITORY :
            case ProductInventory::DRIVER :
                $flag = $class::find($value) ? true : false;
                break;
            case ProductInventory::INVENTORY :
                $flag = $class::hasModel($this->inventoryModelType)->find($value) ? true : false;
                break;
        }
        return $flag;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.exists', ['attribute' => __('product.'.strtolower($this->modelType))]);
    }
}
