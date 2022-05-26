<?php

namespace App\Events\Hub;

use App\Models\Hub\Deal;

class DealCreated
{
    public $deal;
    public $args = [];
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Deal $deal, array $args)
    {
        $this->deal = $deal;
        $this->args =  $args;
    }
}
