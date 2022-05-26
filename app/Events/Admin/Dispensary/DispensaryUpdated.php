<?php

namespace App\Events\Admin\Dispensary;

use App\Models\Admin\Dispensary\Dispensary;

class DispensaryUpdated
{
    public $dispensaryOldObj;
    public $dispensaryUpdatedObj;
    public $args = [];
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Dispensary $dispensaryOldObj, Dispensary $dispensaryUpdatedObj, array $args)
    {
        $this->dispensaryOldObj = $dispensaryOldObj;
        $this->dispensaryUpdatedObj = $dispensaryUpdatedObj;
        $this->args =  $args;
    }
}
