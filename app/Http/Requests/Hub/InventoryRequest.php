<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\ServiceTrait;
use App\Models\Hub\Inventory;

class InventoryRequest extends FormRequest
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
        return [
            'inventoryId' => 'required|exists:inventories,id,dispensary_id,'.tenant('id')
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $inventory = Inventory::hasModel($this->modelType)->find($this->route('inventoryId'));
            if ($inventory === null) {
                $validator->errors()->add('inventoryId', __('message.notFound', ['name' => __('product.inventory')]));
            }
        });
    }
}
