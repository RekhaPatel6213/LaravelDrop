<?php

namespace App\Listeners\Hub;

use App\Events\Hub\InventoryEvent as Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Traits\ServiceTrait;
use App\Jobs\ElasticDispensaryJob;

class InventoryListener implements ShouldQueue
{
    use InteractsWithQueue, ServiceTrait;

    /**
     * Handle the event.
     *
     * @param  \App\Events\Hub\InventoryEvent  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $inventory = $event->inventory->toArray();

        dispatch(new ElasticDispensaryJob(tenant(), ['Product','Reward']));

        if (empty($inventory['metrc_location_id']) && $this->metrcEnable()) {
            //Add Location functionality Pending
        }
        if (!empty($inventory['metrc_location_id']) && $this->metrcEnable()) {
            //Update Location functionality Pending
        }
    }
}
