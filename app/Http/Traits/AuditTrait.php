<?php

namespace App\Http\Traits;

use App\Models\Hub\Product;
use App\Models\Hub\ProductInventory;

trait AuditTrait
{
    public function getProductAllocatedStock(object $inventories, ?int $modelId){
        $inventory = $inventories->first();
        return $inventory && $modelId ? (int) $inventory->stock : 0;
    }

	public function auditProductFormate(Product $product, array $totalAllocatedStocks, ?int $modelId)
    {
        $isPrepack = $product->quantity_type === Product::PREPACKAGED ? true : false;
        return [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'brand' => $product->brand ?? null,
            'quantity_type' => $isPrepack ? Product::UNITS : $product->quantity_type,
            'product_details' => $this->getAuditProductDetail($product, $isPrepack, $totalAllocatedStocks, $modelId)
        ];
    }

    public function getAuditProductDetail(Product $product, bool $isPrepack, $totalAllocatedStocks, ?int $modelId)
    {
        $details = [];
        $isUnlimited = $product->is_unlimited;
        if ($isPrepack) { 
            $variant = [];
            foreach ($product->productDetails as $detail) {
                $allocatedStock = $isUnlimited === Product::NO ? $this->getProductAllocatedStock($detail->inventories, $modelId) : 0;
                $totalAllocatedStock = $isUnlimited === Product::NO && isset($totalAllocatedStocks[$detail->id]) ? $totalAllocatedStocks[$detail->id] : 0;
                $details[] = $this->auditProductDetailFormate($detail->stock, $allocatedStock, $totalAllocatedStock, $isUnlimited, $detail->id, $detail->variant->name);
            }
        }

        if (!$isPrepack) {
            $allocatedStock = $isUnlimited === Product::NO ? $this->getProductAllocatedStock($product->inventories, $modelId) : 0;
            $totalAllocatedStock = $isUnlimited === Product::NO && isset($totalAllocatedStocks[$product->id]) ? $totalAllocatedStocks[$product->id] : 0;
            $details[] = $this->auditProductDetailFormate($product->stock, $allocatedStock, $totalAllocatedStock, $isUnlimited);
        }
        return $details;
    }

    public function auditProductDetailFormate(int $stock, int $allocatedStock = null, int $totalAllocatedStock = null, string $isUnlimited, int $detailId = null, string $variant = null)
    {
        return [
            'product_detail_id' => $detailId,
            'variant' => $variant,
            'is_unlimited' => $isUnlimited,
            'stock' => $isUnlimited === Product::NO ? (int) $stock : 0,
            'allocated_stock' => $allocatedStock,
            'total_allocated_stock' => $totalAllocatedStock,
            'new_stock' => 0,
            'difference_stock' => 0,
            'reason' => 'Product loss due to damage'
        ];
    }

    public function checkAllocatedStock(array $product, string $quantityType, $totalStock)
    {
        if ($quantityType === Product::PREPACKAGED) { 
            foreach ($product['product_details'] as $keyD => $detail) {
                $allocatedStock =  $totalStock->where('product_detail_id', $detail['product_detail_id'])->first();
                if($allocatedStock && $allocatedStock->stock > $detail['new_stock'] && $detail['is_unlimited'] === Product::NO){
                    return [$keyD,false];
                }
            }
            return [null, true];
        }

        $allocatedStock =  $totalStock->first();
        if($allocatedStock && $allocatedStock->stock > $product['product_details'][0]['new_stock'] && $detail['is_unlimited'] === Product::NO){
            return [0,false];
        }
        return [null, true];
    }
}