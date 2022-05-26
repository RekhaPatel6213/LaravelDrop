<?php

namespace App\Services\Hub;

use App\Models\Hub\ProductInventory;
use App\Models\Hub\ProductDetail;
use App\Models\Hub\Product;
use App\Http\Traits\ProductInventoryTrait;
use App\Http\Traits\ProductTrait;
use App\Http\Traits\ServiceTrait;
use App\Models\Activity;
use Illuminate\Support\Facades\Event;
use App\Jobs\ElasticProductJob;
use App\Events\Hub\ProductInventoryLogEvent;
use App\Models\Repositories\Hub\ProductInventoryRepository;
use App\Exceptions\InventoryException;

class ProductInventoryService
{
    Use ProductInventoryTrait, ProductTrait, ServiceTrait;

    protected $repository, $inventoryFeature, $inventoryModelType;

    public function __construct()
    {
        $this->repository = new ProductInventoryRepository;
        $this->inventoryFeature = $this->getInventoryAccess();
        $this->inventoryModelType = $this->getInventoryModelType();
    }

    public function inventoryLogList($requestData)
    {
        $search = $requestData->query('search', null);
        $sortOn = $requestData->query('sortOn', '_id');
        $sortOrder = $requestData->query('sort', 'desc');
        $logs = Activity::where('description', Product::PRODUCT_INVENTORY_HUB)
                        ->when($search !== null, function ($query1) use($search) {
                            $query1->where('properties','LIKE', '%' . $search . '%');
                        })
                        ->orderBy($sortOn, $sortOrder)
                        ->get();
        return $logs;
    }

    public function productDetail(int $productId)
    {
        $modelType = $this->getProductInventoryModelType();
        return Product::with([
                                'inventories' => function ($query) use($modelType) {
                                    $query->with(['model'])->where('model_type', $modelType);
                                 },
                                'productDetails.variant'
                            ])
                            ->find($productId);
    }

    public function addStock(int $productId, array $requestData)
    {
        $detailId = $requestData['product_detail_id'] ?? null;
        $stock = $requestData['stock'];
        $modelType = $this->getProductInventoryModelType();

        $product = Product::with(['productDetails.variant'])->find($productId);
        $pDetails = $product;
        $varientName = $productDetailId = null;
        $stockFunction = 'updateOrCreateProductStock';

        if ($product->quantity_type === Product::PREPACKAGED) {
            $pDetails = $product->productDetails->where('id', $detailId)->first();
            $productDetailId = $pDetails->id;
            $varientName = $pDetails->variant->name;
            $stockFunction = 'updateOrCreateProductDetailStock';
        }

        $oldStock = $pDetails->stock;
        $availableStock = $this->getAvailableStock($product, $modelType, $productDetailId);

        if (($stock < 0 && ($stock) < $pDetails->stock) && ($stock + $availableStock < 0)) {
            throw new InventoryException(__('product.stockLess'));
        }

        $pDetails->stock += $requestData['stock'];
        $pDetails->save();

        $this->$stockFunction($pDetails);
        event(new ProductInventoryLogEvent($product, $pDetails->stock, $oldStock, $productDetailId, $varientName));

        return ['message' => __('message.updateSuccess', ['name' => __('product.productDetail')])];
    }

    public function inventoryStore(int $productId, array $requestData)
    {
        $stock = $requestData['stock'];
        $modelIds = $requestData['model_ids'];
        $modelType = $requestData['model_type'];
        $totalStock = $stock * count($modelIds);
      
        $product = $this->productDetail($productId);
        $productDetailId = $requestData['product_detail_id'] = $product->quantity_type === Product::PREPACKAGED ? ($requestData['product_detail_id'] ?? null) : null;

        $availableStock = $this->getAvailableStock($product, $modelType, $productDetailId);
        $inventories = $this->getInventories($product, $modelType, $modelIds, $productDetailId);
        $inventoryIds = $inventories ? data_get($inventories, '*.model_id') : [];

        if($availableStock < $totalStock){
            throw new InventoryException(__('product.stockNotSufficient'));
        }
        
        //Update Inventories
        $noModelIds = $this->updateProductInventory($inventories, $stock);

        //create New Inventories
        $modelIds = array_diff($modelIds, $inventoryIds);
        $this->createProductInventory($modelIds, $requestData);
        
        dispatch(new ElasticProductJob($product));

        if($noModelIds){
            throw new InventoryException(__('message.inventoryNotFound', ['name' => implode(', ', $noModelIds), 'type' => $this->inventoryModelType]));
        }
        return ['message' => __('message.updateSuccess', ['name' => __('product.productInventory')])];
    }

    public function updateProductInventory(object $inventories, int $stock){
        $inventoryIds = [];
        if ($inventories) {
            foreach ($inventories as $inventory) {
                if ($this->inventoryFeature) {
                    if (
                        $inventory->model->modelInventory && 
                        $inventory->model->modelInventory->first()->model_type === $this->inventoryModelType
                    ){
                        $this->repository->inventoryUpdate($inventory, $stock); 
                    } else {
                        \array_push($inventoryIds, $inventory->model_id);
                    }
                }

                if(!$this->inventoryFeature){
                    $this->repository->inventoryUpdate($inventory, $stock);
                }
            }  
        }
        return $inventoryIds;
    }

    public function createProductInventory(array $modelIds, array $requestData)
    {
        if(count($modelIds) > 0){
            foreach ($modelIds as $modelId) {
                $this->repository->newProductInventory($requestData, $modelId, $requestData['model_type'], true); 
            }
        }
    }

    public function inventoryReallocate(int $inventoryId, array $requestData)
    {
        $stock = $requestData['stock'];
        $inventory = ProductInventory::find($inventoryId);

        if($inventory->stock >= $stock){
            $this->repository->inventoryUpdate($inventory, -$stock, ProductInventory::HUBTRANSFER);

            if($requestData['is_unallocated'] === ProductInventory::YES){
                return ['message' => __('product.reallocationSuccess')];
            }

            $reallocateModelId = $requestData['model_id'];
            $this->repository->updateProductInventory($inventory, $reallocateModelId, $stock, null, ProductInventory::HUBTRANSFER);
            return ['message' => __('product.reallocationSuccess')];
        }
        throw new InventoryException(__('product.stockNotSufficient'));
    }

    public function delete(int $productId, int $modelId)
    {
        ProductInventory::where('product_id', $productId)->where('model_id', $modelId)->delete();
        return ['message' => __('message.deleteSuccess', ['name' => __('product.productInventory')])];
    }
}