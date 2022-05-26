<?php

namespace App\Events\Hub;

use App\Models\Hub\Inventory;

class InventoryEvent
{
    public Inventory $inventory;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }
}
