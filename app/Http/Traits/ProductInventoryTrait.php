<?php

namespace App\Http\Traits;

use App\Models\Hub\Product;
use App\Models\Hub\ProductDetail;
use App\Http\Traits\ServiceTrait;
use App\Models\Hub\ProductInventory;
use App\Models\Hub\BulkTransfer;
use App\Models\Activity;

trait ProductInventoryTrait
{
    use ServiceTrait;

    protected $inventoryModelType;
    public function __construct()
    {
        $this->inventoryModelType = $this->getInventoryModelType();
    }

	public function getInventories(Product $product, string $modelType, array $modelIds = null, int $productDetailId = null)
    {
        $inventories = $product->inventories->where('model_type', $modelType);

        if($modelIds !== null){
            $inventories = $inventories->whereIn('model_id', $modelIds);
        }

        if($productDetailId !== null){
            $inventories = $inventories->where('product_detail_id', $productDetailId);
        }
        return $inventories;
    }

    public function getAvailableStock(Product $product, string $modelType, int $productDetailId = null)
    {   
        $stock =  $productDetailId !== null ? $product->productDetails->where('id', $productDetailId)->first()->stock : $product->stock;
        $inventories = $this->getInventories($product, $modelType, null, $productDetailId);
        $inventoryStock = $this->getInventorySum($inventories);
        return $stock - $inventoryStock;
    }

    public function getInventorySum(object $inventories)
    {
        $sum = 0;
        if ($inventories) {
            if($this->getInventoryAccess()){
                foreach($inventories  as $key => $inventory){
                    $modelInventories = $inventory->model->modelInventory->where('model_type', $this->inventoryModelType);
                    if(count($modelInventories) > 0){
                        $sum += $inventory->stock;
                    }
                }
                return $sum;
            }
            return $inventories->sum('stock');
        }
        return 0;
    }


    public function checkBulkTransferStock(Product $product, int $productDetailId = null, int $stock, int $fromInventoryId, bool $isValidate = false)
    {
        $modelType = ProductInventory::INVENTORY;

        list($inventory, $inventoryStock) = app('bulkTransferService')->getInventoryInfo($modelType, $fromInventoryId, $productDetailId, $product, $isValidate);

        if ($inventoryStock > 0 && $stock > 0 && $inventoryStock < $stock) {
            return false;
        }
        return true;
    }

    public function bulkProductFormate(Product $product, int $productDetailId = null, int $stock, array $toProducts)
    {
        $isPrepack = $product->quantity_type === Product::PREPACKAGED ? true : false;
        return [
            'id' => $product->id,
            'product_detail_id' => $productDetailId,
            'name' => $product->name,
            'logo' => $product->hasMedia('logo') ? $product->getFirstMedia('logo')->getUrl('thumb') : NULL,
            'isPrePack' => $isPrepack,
            'quantity_type' => $isPrepack ? Product::UNITS : $product->quantity_type,
            'from_inventory_stock' => !$isPrepack ? $stock : 0,
            'to_inventory_stock' => !$isPrepack && isset($toProducts[$product->id])? $toProducts[$product->id] : 0,
        ];
    }

    public function bulkProductDetailFormate(int $productDetailId, int $stock, string $variantName, array $toProductDetails)
    {
        return [
            'from_inventory_stock' => $stock,
            'to_inventory_stock' => isset($toProductDetails[$productDetailId]) ? $toProductDetails[$productDetailId] : 0,
            'variant' => $variantName,
            'product_detail_id' => $productDetailId,
        ];
    }

    public function getProductInventoryStock($product, int $inventoryId)
    {
        $stockSum = $product->inventories->sum('stock');
        return $inventoryId !== BulkTransfer::UNALLOCATED_ID ? $stockSum : ($product->stock - $stockSum) ;
    }

    public function getBulkProductLogDetails(int $transferId)
    {
        $products = [];
        $activities = Activity::forBulkTransferId($transferId)->get();
        if ($activities) {
            foreach ($activities as $activity) {
                $productId = $activity->properties['product_id'];
                $productDetailId = $activity->properties['product_detail_id'];
                $stock = $activity->properties['stock'];

                if($stock < 0){
                    $products[$productId][] = [
                        'id' => $productId,
                        'product_detail_id' => $productDetailId,
                        'name' => $activity->properties['product_name'],
                        'variante' => $activity->properties['variant'],
                        'quantity_type' => $activity->properties['quantity_type'] ?? Product::UNITS,
                        'transferred_stock' => abs($stock),
                        'from_inventory_stock' => ($activity->properties['product_stock'] - $stock),
                        'from_current_stock' => $activity->properties['product_stock']
                    ];
                }

                if($stock > 0){
                    $key = array_search($productDetailId, array_column($products[$productId], 'product_detail_id'));
                    $products[$productId][$key]['to_inventory_stock'] = ($activity->properties['product_stock'] - $stock);
                    $products[$productId][$key]['to_current_stock'] = $activity->properties['product_stock'];
                }
            }
        }
        return $products;
    }

    public function getTotalInventoryStock(?string $modelType, ?array $productIds)
    {
        return ProductInventory::selectRaw("sum(stock) as stock, product_id, product_detail_id" )    
                                        ->inventoryHasMorph($modelType, $this->inventoryModelType)
                                        ->ofModelType($modelType)
                                        ->groupBy('product_id','product_detail_id')
                                        ->whereIn('product_id', $productIds)
                                        ->get();
    }
}