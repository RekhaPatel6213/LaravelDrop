<?php

namespace App\Events\Admin\Dispensary;

use App\Models\Admin\Dispensary\Dispensary;
use App\Models\Admin\Dispensary\DispensaryHourSet;

class DispensaryHourSetCreated
{
    public $dispensary;
    public $dispensaryHourSet;

    /**
     * Create a new event instance.
     *
     * @return void
     * @param Dispensary $dispensary
     * @param DispensaryHourSet $dispensaryHourSet
     */
    public function __construct(Dispensary $dispensary, DispensaryHourSet $dispensaryHourSet)
    {
        $this->dispensary = $dispensary;
        $this->dispensaryHourSet =  $dispensaryHourSet;
    }
}
