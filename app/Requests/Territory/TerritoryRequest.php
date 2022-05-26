<?php

namespace App\Http\Requests\Territory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Territory\Territory;
use App\Models\Territory\TerritoryModule;
use App\Models\Hub\Inventory;
use App\Models\Hub\ProductInventory;
use App\Models\Hub\ModelInventory;
use App\Http\Traits\ServiceTrait;

class TerritoryRequest extends FormRequest
{
    use ServiceTrait;

    protected $modelType;

    public function __construct()
    {
        $this->modelType = ProductInventory::TERRITORY;
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
        if($this->route('id')){
            $request['id'] = $this->route('id');
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
        $id = $this->id ?? null;
        $dispensaryId = tenant('id') ?? 'NULL';

        $validationRules = [
            'id' => 'sometimes|numeric|exists:territories,id,dispensary_id,'.$dispensaryId,
            'type' => ['sometimes', 'required', Rule::in([Territory::ZIPCODE,Territory::GEO])],
            'name' => 'required|max:50|unique:territories,name,'.$id .',id,dispensary_id,' . $dispensaryId,
            'driver_ids' => 'nullable|sometimes|required|array',
            'driver_ids.*' => 'exists:driver_users,id,dispensary_id,'.$dispensaryId,
            'dispensary_user_ids' => 'nullable|array',
            'dispensary_user_ids.*' => 'numeric|exists:dispensary_users,id,dispensary_id,'.$dispensaryId,
            'inventory_id' =>  'nullable|numeric|exists:inventories,id,dispensary_id,'.$dispensaryId,
            'geo_points' => 'required_if:type,'.Territory::GEO.'|array',
        ];

        if($this->type === Territory::ZIPCODE){
            $validationRules['location_ids'] = 'required|array';
            $validationRules['location_ids.*'] = 'exists:locations,id';
        }

        if($this->type === Territory::GEO){
            $validationRules['geo_points'] = 'required|array';
            $validationRules['minimum_order_cost'] = 'required|numeric';
            $validationRules['delivery_fee'] = 'required|numeric';
            $validationRules['cost_for_free_delivery'] = 'required|numeric';
        }

        return $validationRules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $territoryId = $this->route('id');
            if($this->location_ids){
                $locationIds = TerritoryModule::notTerritoryId($territoryId)->whereIn('module_id', $this->location_ids)->where('module_type', TerritoryModule::LOCATION)->pluck('module_id')->where('dispensary_id', '!=', tenant('id'))->toArray();

                if($locationIds){
                    $validator->errors()->add('location_ids', __('validation.exists', ['attribute' => __('territory.location') .' '. implode(', ', $locationIds)]));
                }
            }

            if ($this->inventory_id) {

                $modelIds = $territoryId ? [$territoryId] : null;
                $model = ModelInventory::inInventory($this->inventory_id)->OfModelType($this->modelType)->notInModelId([$territoryId])->pluck('model_id')->toArray();
                if($model){
                    $validator->errors()->add('inventory_id', __('message.exist', ['name' => __('product.inventory')]));
                }
            }
        });
    }
}
