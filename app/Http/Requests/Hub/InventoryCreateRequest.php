<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Hub\Inventory;
use App\Models\Hub\ModelInventory;
use App\Models\Hub\ProductInventory;
use App\Http\Traits\ServiceTrait;

class InventoryCreateRequest extends FormRequest
{
    use ServiceTrait;

    protected $modelType;

    public function __construct()
    {
        $this->modelType = $this->getInventoryModelType();
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
        if($this->route('inventoryId')){
            $request['inventoryId'] = $this->route('inventoryId');
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
        $table = app(ProductInventory::MODELCLASS[$this->model_type??$this->modelType])->getTable();
        $inventoryId = isset($this->inventoryId) ? $this->inventoryId : null;

        $validationRules = [
            'inventoryId' => 'sometimes|numeric|exists:inventories,id,dispensary_id,'.tenant('id'),
            'name' => 'required|max:100|unique:inventories,name,'.$inventoryId.',id,dispensary_id,'.tenant('id'),
            'is_sale' => ['required', Rule::in(Inventory::IS_SALE)],
            'model_type' => ['required', Rule::in([$this->modelType])],
            'model_ids' => 'sometimes|required_with:model_type|array',
            'model_ids.*' => 'numeric|exists:'.$table.',id,dispensary_id,'.tenant('id'),
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

        return $validationRules;
    }

    public function messages()
    {
        return [
            'model_id.required' => __('validation.required', ['attribute' => $this->model_type]),
            'model_type.in' => __('validation.exists', ['attribute' => __('product.modelType')]).__('product.itShouldBe', ['name' => $this->modelType]),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $inventoryFlag = true;
            $inventoryId = $this->route('inventoryId');
            if($inventoryId){
                $inventory = Inventory::withModel($this->modelType)->find($inventoryId);

                if($inventory === null){
                    $validator->errors()->add('inventoryId', __('message.notFound', ['name' => __('product.inventory')]));
                    $inventoryFlag = false;
                }
            }

            if ($this->model_ids && $inventoryFlag) {
                $model = ModelInventory::notInInventory($inventoryId)->OfModelType($this->modelType)->InModelId($this->model_ids)->pluck('model_id')->toArray();

                if($model){
                    $validator->errors()->add('model_id', __('message.exist', ['name' => __('product.modelId') .' '. implode(', ', $model)]));
                }
            }
        });
    }
}
