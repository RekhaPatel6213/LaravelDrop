<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Hub\ProductInventory;
use App\Models\Territory\Territory;
use App\Models\Driver\DriverUser;
use App\Http\Traits\ServiceTrait;

class ReallocateInventoryRequest extends FormRequest
{
     use ServiceTrait;

    protected $modelType;

    public function __construct()
    {
        $this->modelType = $this->getProductInventoryModelType();
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
        $request['inventoryId'] = $this->route('inventoryId');
        return $request;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $table = app(ProductInventory::MODELCLASS[$this->modelType])->getTable();

        return [
            'inventoryId' => 'required|numeric|exists:product_inventories,id',
            'stock' => 'required|numeric|gt:0',
            'model_id' => 'required|numeric|exists:'.$table.',id,dispensary_id,'.tenant('id'),
            'is_unallocated' => ['required', Rule::in([ProductInventory::YES, ProductInventory::NO])],
        ];
    }

    public function messages()
    {
        return [
            'inventoryId.exists' => __('message.notFound', ['name' => __('product.productInventory')]),
            'model_id.exists' => __('message.notFound', ['name' => $this->modelType]),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $inventory = ProductInventory::find($this->route('inventoryId'));
            if ($inventory && $this->model_id === $inventory->model_id) {
                $validator->errors()->add('productDetailId', __('product.sameReallocateNot'));
            }
        });
    }
}
