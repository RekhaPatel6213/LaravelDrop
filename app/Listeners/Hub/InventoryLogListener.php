<?php

namespace App\Listeners\Hub;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Hub\InventoryLogEvent as Event;
use App\Models\Hub\ProductInventory;
use App\Models\Hub\Product;
use App\Objects\ActivityLogger;

class InventoryLogListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $inventory = $event->inventory;
        $stock = $event->stock;
        $actionType = $event->actionType ?? ProductInventory::HUBINVENTORY;
        $product = Product::find($inventory->product_id);

        $fromInventoryId = $fromInventoryName = $toInventoryId = $toInventoryName = null;
        if(ProductInventory::BULKTRANSFER === $actionType){
            $fromInventoryId = $stock < 0 ? $inventory->model_id : $event->inventoryId;
            $fromInventoryName = $stock < 0 ? $inventory->model->name : $event->inventoryName;
            $toInventoryId = $stock > 0 ? $inventory->model_id : $event->inventoryId;
            $toInventoryName = $stock > 0 ? $inventory->model->name : $event->inventoryName;
        }

        app(ActivityLogger::class)
            ->performedOn($product)
            ->causedBy(Auth()->user())
            ->withProperties([
                'dispensary_id' => tenant('id'),
                'product_id' => $inventory->product_id,
                'product_name' => $inventory->product->name,
                'product_detail_id' => $inventory->product_detail_id,
                'variant' => $inventory->product_detail_id ? $inventory->productDetail->variant->name : null,
                'stock' => $stock,
                'product_stock' => $inventory->stock,
                'action' => $actionType,
                'model_id' => $inventory->model_id,
                'model_type' => $inventory->model_type,
                'model_name' => $inventory->model->name,
                'added_by' => Auth()->user()->full_name,
                'order_id' => null,
                'bulk_transfer_id' => $event->bulkTransferId,
                'from_inventory_id' => $fromInventoryId,
                'from_inventory_name' => $fromInventoryName,
                'to_inventory_id' => $toInventoryId,
                'to_inventory_name' => $toInventoryName,
                'quantity_type' => $product->quantity_type === Product::PREPACKAGED ? Product::UNITS : $product->quantity_type,
            ])
            ->bulkTransferId($event->bulkTransferId)
            ->log(Product::PRODUCT_INVENTORY_HUB);
    }
}
