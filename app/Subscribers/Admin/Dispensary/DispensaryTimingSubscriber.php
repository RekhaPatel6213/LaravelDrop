<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryHourSetCreated;
use App\Models\Repositories\Admin\Dispensary\DispensaryTimingRepository;
use Carbon\Carbon;

class DispensaryTimingSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $repository;
    public function __construct(DispensaryTimingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  DispensaryHourSetCreated  $event
     * @return void
     */
    public function handleDispensaryHourSetCreated(DispensaryHourSetCreated $event)
    {
        $dispensaryHourSet = $event->dispensaryHourSet;
        $currentTimestamp = Carbon::now()->toDateTimeString();
        $data = [];
        foreach (config('constants.DAYNUMBERS_OF_WEEK') as $dayId) {
            $data[] = [
                'dispensary_hour_set_id' => $dispensaryHourSet->id,
                'day' => $dayId,
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ];
        }

        $this->repository->insertData($data);
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryHourSetCreated',
            'App\Subscribers\Admin\Dispensary\DispensaryTimingSubscriber@handleDispensaryHourSetCreated'
        );
    }
}
