<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryUpdated;
use App\Models\Admin\Dispensary\Dispensary;

class ActivityLogSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DispensaryUpdated  $event
     * @return void
     */
    public function handleDispensaryUpdated(DispensaryUpdated $event)
    {
        $dispensaryUpdatedObj = $event->dispensaryUpdatedObj;
        if (in_array($dispensaryUpdatedObj->status, array_keys(Dispensary::LOG_ACTIONS))) {
            activity()
                ->performedOn($dispensaryUpdatedObj)
                ->log(Dispensary::LOG_ACTIONS[$dispensaryUpdatedObj->status]);
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryUpdated',
            'App\Subscribers\Admin\Dispensary\ActivityLogSubscriber@handleDispensaryUpdated'
        );
    }
}
