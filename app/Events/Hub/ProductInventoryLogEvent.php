<?php

namespace App\Events\Hub;

use App\Models\Hub\Product;

class ProductInventoryLogEvent
{
    public Product $product;
    public int $stock;
    public int $oldStock;
    public ?int $productDetailId;
    public ?string $variantName = null;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Product $product, int $stock, int $oldStock, int $productDetailId = null, string $variantName = null)
    {
        $this->product = $product;
        $this->stock = $stock;
        $this->oldStock = $oldStock;
        $this->productDetailId = $productDetailId;
        $this->variantName =  $variantName;
    }
}
