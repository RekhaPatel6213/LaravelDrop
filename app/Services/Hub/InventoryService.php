<?php
namespace App\Services\Hub;

use App\Models\Hub\Inventory;
use App\Models\Hub\ProductInventory;
use App\Models\Repositories\Hub\InventoryRepository;
use App\Http\Traits\ServiceTrait;
use App\Http\Traits\OrderTrait;
use Illuminate\Support\Facades\Event;
use App\Events\Hub\InventoryEvent;


class InventoryService
{
    use ServiceTrait, OrderTrait;
    private $inventoryRepository;
    public $modelType;

    public function __construct( InventoryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
        $this->modelType = $this->getInventoryModelType();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getListing($request)
    {
        $sortOn = 'created_at';
        $sortOrder = $request->query('sort', Inventory::DEFAULT_LIST_ORDER);
        $searchString = $request->query('name', '');

        return $this->inventoryRepository->getListingData($searchString, $sortOn, $sortOrder);
    }

    /**
     * @param $request
     * @param $inventoryId
     * @return array
     */
    public function update(array $requestData, int $inventoryId = null)
    {
        if ($inventoryId === null) {
            $inventory = $this->inventoryRepository->create($requestData, $inventoryId);
        } elseif ($inventoryId) {
            $this->checkPendingOrders('change',$this->modelType);
            $inventory = $this->inventoryRepository->update($requestData, $inventoryId);
        }
        $inventoryId = $inventory->id;
        $this->inventoryRepository->updateOrCreateInventory($inventoryId, $requestData);

        event(new InventoryEvent($inventory));
        return $this->getInventory($inventoryId);
    }

    /**
     * @param int $inventoryId
     * @return mixed
     */
    public function getInventory(int $inventoryId)
    {
        return $this->inventoryRepository->withModel($this->modelType)->find($inventoryId);
    }

    /**
     * @param int $inventoryId
     * @return array
     */
    public function delete(int $inventoryId)
    {
        $this->checkPendingOrders('change',$this->modelType);
        ProductInventory::ofModelType(ProductInventory::INVENTORY)->ofModelId($inventoryId)->delete();
        $this->inventoryRepository->delete($inventoryId);
        return ['message' => __('message.deleteSuccess', ['name' => __('product.inventory')])];
    }
}
