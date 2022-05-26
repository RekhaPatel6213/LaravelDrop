<?php

namespace App\Events\Hub;

use App\Models\Admin\Dispensary\DispensaryUser;

class DispensaryUserEvent
{
    public $dispensaryUser;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DispensaryUser $dispensaryUser)
    {
        $this->dispensaryUser = $dispensaryUser;
    }
}
