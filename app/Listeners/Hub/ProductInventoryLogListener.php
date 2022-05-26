<?php

namespace App\Listeners\Hub;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Hub\ProductInventoryLogEvent as Event;
use App\Models\Hub\Product;

class ProductInventoryLogListener implements ShouldQueue
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
        $product = $event->product;
        $stock = $event->stock;
        $oldStock = $event->oldStock;
        $productDetailId = $event->productDetailId;
        $variantName = $event->variantName;

        if ((int)$stock !== (int)$oldStock) {
        	$calStock = ($stock - $oldStock);
	        activity() 
	            ->performedOn($product)
	            ->causedBy(Auth()->user())
	            ->withProperties([
	                'product_id' => $product->id,
	                'product_name' => $product->name,
	                'product_detail_id' => $productDetailId ?? null,
	                'variant' => $variantName ?? null,
	                'stock' => $calStock !== 0 ? $calStock : $stock,
	                'product_stock' => $stock,
	                'action' => 'Hub Product Inventory',
	                'added_by' => Auth()->user()->full_name,
	                /*'model_id' => null,
	                'model_type' => null,
	                'model_name' => null,
	                'order_id' => null,*/
	            ])
            	->log(Product::PRODUCT_INVENTORY_HUB);
        }
    }
}
