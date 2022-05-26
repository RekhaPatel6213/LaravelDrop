<?php

namespace App\Rules\Hub;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Traits\ServiceTrait;
use App\Models\Hub\Inventory;

class CustomInventoryRule implements Rule
{
    use ServiceTrait;

    protected $inventoryModelType;

    public function __construct()
    {
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
        return Inventory::hasModel($this->inventoryModelType)->find($value) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.exists', ['attribute' => __('product.inventory')]);
    }
}
