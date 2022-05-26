<?php

namespace App\Models\Repositories\Hub;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Hub\Inventory;
use App\Models\Hub\ModelInventory;
use App\Http\Traits\ServiceTrait;

/**
 * Class InventoryRepository.
 *
 * @package namespace App\Repositories\Hub;
 */
class InventoryRepository extends BaseRepository
{
    use ServiceTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Inventory::class;
    }

    /**
     * Vendor listing data
     *
     * @return mixed
     */
    public function getListingData(string $searchString, string $sortOn, string $sortOrder)
    {
        $modelType = $this->getInventoryModelType();
        $query = $this->model->select('*')->with(['modelInventory.model'])->hasModel($modelType);
        if (!empty($searchString)) {
            $query->where( function($query1) use($searchString) {
                foreach (Inventory::SEARCH_FIELDS as $field) {
                    $query1->orWhere($field, 'LIKE', '%' . $searchString . '%');
                }
            });
        }
        return $query->orderBy($sortOn, $sortOrder)->get();
    }

    /***
     * @param Inventory $inventory
     * @param array $requestData
     */
    public function updateOrCreateInventory(int $inventoryId, array $requestData){
        $modelIds = $requestData['model_ids'] ?? null;
        $modelType = $requestData['model_type'] ?? $this->getInventoryModelType();

        if($modelIds && $modelType){
            $inventories = ModelInventory::ofModelType($modelType)->where('inventory_id', $inventoryId)->withTrashed()->get();

            ModelInventory::ofModelType($modelType)->inModelId($modelIds)->where('inventory_id', $inventoryId)->restore();

            foreach ($modelIds as $modelId){
                if(!$inventories->where('model_id', $modelId)->first()){
                    $modelInventory = new ModelInventory();
                    $modelInventory->inventory_id = $inventoryId;
                    $modelInventory->model_type = $modelType;
                    $modelInventory->model_id = $modelId;
                    $modelInventory->save();
                }
            }

            ModelInventory::where('inventory_id', $inventoryId)->where('model_type', $modelType)->whereNotIn('model_id', $modelIds)->delete();
        }
    }
}
