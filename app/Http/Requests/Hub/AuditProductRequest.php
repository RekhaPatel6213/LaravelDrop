<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\ServiceTrait;
use Illuminate\Validation\Rule;
use App\Models\Hub\ProductInventory;
use App\Rules\Hub\InventoryModelIdRule;

class AuditProductRequest extends FormRequest
{
    use ServiceTrait;

    protected $modelType;

    public function __construct()
    {
        $this->modelType = [$this->getProductInventoryModelType(), ProductInventory::CATEGORY];
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
        $table = app(ProductInventory::MODELCLASS[$this->model_type])->getTable();

        return [
            'model_type' => ['required', Rule::in($this->modelType)],
            'model_id' => ['required', 'numeric', new InventoryModelIdRule($this->model_type)],
        ];
    }

    public function messages()
    {
        return [
            'model_type.in' => __('validation.exists', ['attribute' => __('product.modelType')]).__('product.itShouldBe', ['name' => implode(' Or ',$this->modelType)]),
        ];
    }
}
