<?php

namespace App\Events\Admin\Customer;

use App\Models\Admin\Customer\Customer;

class CustomerCreated
{
    public $customer;
    public $args = [];
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, array $args)
    {
        $this->customer = $customer;
        $this->args =  $args;
    }
}
