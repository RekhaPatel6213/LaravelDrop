<?php

namespace App\Events\Driver;

use App\Models\Driver\DriverUser;

class DriverUserCreated
{
    public $driver;
    public $args = [];
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DriverUser $driver, array $args)
    {
        $this->driver = $driver;
        $this->args =  $args;
    }
}
