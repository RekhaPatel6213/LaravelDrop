<?php

namespace App\Events\Hub;

use App\Models\Hub\ProductInventory as Inventory;

class InventoryLogEvent
{
    public Inventory $inventory;
    public int $stock;
    public ?string $actionType;
    public ?int $inventoryId;
    public ?string $inventoryName;
    public ?int $bulkTransferId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Inventory $inventory, int $stock, string $actionType = null, int $inventoryId = null, string $inventoryName = null, int $bulkTransferId = null)
    {
        $this->inventory = $inventory;
        $this->stock = $stock;
        $this->actionType = $actionType;
        $this->inventoryId = $inventoryId;
        $this->inventoryName = $inventoryName;
        $this->bulkTransferId = $bulkTransferId;
    }
}
