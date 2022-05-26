<?php

namespace App\Events\Admin\Dispensary;

use App\Models\Admin\Dispensary\Dispensary;

class DispensaryCreated
{
    public $dispensary;
    public $args = [];
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Dispensary $dispensary, array $args)
    {
        $this->dispensary = $dispensary;
        $this->args =  $args;
    }
}
