<?php

namespace App\Events\Driver;

use App\Models\Driver\DriverUser;

class DriverUserUpdated
{
    public $driverOldObj;
    public $driverUpdatedObj;
    public $args = [];
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DriverUser $driverOldObj, DriverUser $driverUpdatedObj, array $args)
    {
        $this->driverOldObj = $driverOldObj;
        $this->driverUpdatedObj = $driverUpdatedObj;
        $this->args =  $args;
    }
}
