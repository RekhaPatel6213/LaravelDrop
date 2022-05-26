<?php

namespace App\Models\Repositories\Hub;

use App\Models\Repositories\BaseRepository;
use App\Models\Hub\ProductInventory;
use App\Events\Hub\InventoryLogEvent;
use App\Models\Territory\Territory;
use App\Models\Driver\DriverUser;
use App\Models\Hub\Inventory;
use App\Models\Hub\ModelInventory;
use App\Models\Hub\Product;
use App\Models\Activity;
use App\Http\Traits\ServiceTrait;
use App\Http\Traits\ProductInventoryTrait;
use App\Models\Hub\BulkTransfer;
use DB;

class ProductInventoryRepository
{
    use ServiceTrait, ProductInventoryTrait;


    protected $inventoryModelType;

    public function __construct()
    {
        $this->inventoryModelType = $this->getInventoryModelType();
    }


    public function inventoryUpdate(ProductInventory $inventory, int $stock, string $actiontype = null, int $inventoryId = null, string $inventoryName = null, int $bulkTransferId = null)
    {
        $inventory->stock += $stock;
        $inventory->save();
        event(new InventoryLogEvent($inventory, $stock, $actiontype, $inventoryId, $inventoryName, $bulkTransferId));
    }

    public function updateProductInventory(ProductInventory $inventory, int $reallocateModelId, int $stock, string $modelType = null, string $actiontype = null)
    {
        $productDetailId = $inventory->product_detail_id;
        $modelType = $modelType ?? $inventory->model_type;
        $newInventory = ProductInventory::where('product_detail_id', $productDetailId)
                                                    ->where('model_id', $reallocateModelId)
                                                    ->where('model_type', $modelType)
                                                    ->first();
        if(null === $newInventory) {
            $data = [
                'productId' => $inventory->product_id,
                'product_detail_id' => $inventory->product_detail_id,
                'stock' => $stock
            ];
            return $this->newProductInventory($data, $reallocateModelId, $modelType, $actiontype, true);
        }
        return $this->inventoryUpdate($newInventory, $stock, $actiontype); 
    }

    public function newProductInventory(array $requestData, int $modelId, string $modelType, string $actiontype = null, bool $isLogEvent = false)
    {
        $inventory = new ProductInventory();
        $inventory->product_id = $requestData['productId'];
        $inventory->product_detail_id = $requestData['product_detail_id'] ?? null;
        $inventory->model_type = $modelType;
        $inventory->model_id = $modelId;
        $inventory->stock += $requestData['stock'];
        $inventory->save();

        if ($isLogEvent) {
            event(new InventoryLogEvent($inventory, $requestData['stock'], $actiontype));
        }
        return $inventory;
    }

    public function inventoryAccess(bool $inventoryAccess)
    {
        $modelType = $this->getInventoryFeatureModelType();

        if($inventoryAccess){
            return $this->inventoryAccessEnable($inventoryAccess, $modelType);
        }

        $this->allocateInventories($inventoryAccess, $modelType);
    }

    public function inventoryAccessEnable(bool $inventoryAccess, string $modelType)
    {
        $modelClassName = ProductInventory::MODELCLASS[$modelType];

        $models = app($modelClassName)->pluck('name', 'id')->toArray();

        $inventoryModels = ModelInventory::ofModelType($modelType)->inModelId(array_keys($models))->get();

        if ($models) {
            foreach ($models as $modelId => $modelName) {
                $modelName = empty($modelName) ? app($modelClassName)->find($modelId)->name : $modelName;
                $inventoryId = $this->CheckAndCreateInventory($modelType, $modelId, $modelName, $inventoryModels);
                $this->allocateInventories($inventoryAccess, $modelType, [$modelId], $inventoryId, true);
            }
        } 
        return true;
    }

    public function CheckAndCreateInventory(string $modelType, int $modelId, string $modelName, $inventoryModels)
    {
        $inventoryModel = $inventoryModels->where('model_id', $modelId)->first();

        if ($inventoryModel) {
            return $inventoryModel->inventory_id;
        }

        if (!$inventoryModel) {
            $inventory = self::createInventory($modelId, $modelName, $modelType); //Create New Inventory with Model
            return $inventory->id;
        }
    }

    public function allocateInventories(bool $inventoryAccess, string $modelType, array $modelId = null, int $inventoryId = null, bool $checkUnlimited = false)
    {
        $productInventories = $this->getProductInventoryByModel($modelType, $modelId, $checkUnlimited);
        if ($productInventories) {
            foreach ($productInventories as $productInventory) {

                $stock = $productInventory->stock;

                $this->inventoryUpdate($productInventory, -$stock, $inventoryAccess ? ProductInventory::INVENTORYTRANSFER : null);
                
                if ($inventoryAccess && $inventoryId) {
                    $this->updateProductInventory($productInventory, $inventoryId, $stock, ProductInventory::INVENTORY, ProductInventory::INVENTORYON);
                }
            }
        }
    }

    public function getProductInventoryByModel(string $modelType, array $modelId = null, bool $checkUnlimited = false, int $productId = null, int $productDetailId = null, array $with = null)
    {
        $inventory = ProductInventory::ofModelType($modelType)
                    ->inModelId($modelId)
                    ->ofProductId($productId)
                    ->ofProductDetailId($productDetailId)
                    ->where('stock', '>', 0)
                    ->hasProduct($checkUnlimited)
                    ->ofWith($with);

        if($productId || $productDetailId){
            return $inventory->first();
        }

        return $inventory->get();
    }

    private function createInventory(int $modelId, string $modelName, string $modelType)
    {
        $inventory = Inventory::create(['name' => ProductInventory::INVENTORY.' '.$modelName]);

        $data['model_ids'] = [$modelId];
        $data['model_type'] = $modelType;
        InventoryRepository::updateOrCreateInventory($inventory->id, $data);

        return $inventory;
    }

    public function getBulkTransferDetail(int $transferId)
    {
        return BulkTransfer::with(['fromInventory','toInventory'])->findOrFail($transferId);
    }

    public function getProductWithInventoryByModel(string $modelType, ?int $modelId, ?array $with, ?string $unlimited = Product::NO, ?int $categoryId)
    {
        $withInventory = [
            'productDetails' => function ($query) use($modelType, $modelId) {
                $query->with(['variant', 'inventories' => function ($query) use($modelType, $modelId) {
                                    $query->ofModelType($modelType)->ofModelId($modelId);
                              }
                            ]);
            },
            'inventories' => function ($query) use($modelType, $modelId) {
                $query->ofModelType($modelType)->ofModelId($modelId)->whereNull('product_detail_id');
            }
        ];

        if ($with) {
            $withInventory = array_merge($with, $withInventory);
        }

        $product = Product::with($withInventory)->ofActiveState()->ofUnlimited($unlimited);

        if($modelId !== null){
            $product = $product->hasInventory($modelType, $this->inventoryModelType);
        }

        if($categoryId !== null){
            $product =  $product->hasTaxon($categoryId);
        }
        $products = $product->get();
        return $products;
    }

    public function getTotalInventoryStock(string $modelType, array $productIds)
    {
        return ProductInventory::selectRaw("sum(stock) as stock, product_id, product_detail_id" )    
                                        ->inventoryHasMorph($modelType, $this->inventoryModelType)
                                        ->ofModelType($modelType)
                                        ->groupBy('product_id','product_detail_id')
                                        ->whereIn('product_id', $productIds)
                                        ->get();
    }
}
