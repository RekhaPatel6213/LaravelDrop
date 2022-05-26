<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Models\Admin\Dispensary\DropOffOption;
use App\Models\Repositories\Admin\Dispensary\DropOffOptionRepository;
use Carbon\Carbon;

class DropOffOptionSubscriber
{
    protected $repository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DropOffOptionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  DispensaryCreated  $event
     * @return void
     */
    public function handleDispensaryCreated(DispensaryCreated $event)
    {
        $dispensary = $event->dispensary;
        $currentTimestamp = Carbon::now()->toDateTimeString();
        $data = [];
        foreach (DropOffOption::DROP_OFF_OPTIONS as $options) {
            $data[] = [
                'dispensary_id' => $dispensary->id,
                'slug' => $options['slug'],
                'title' => $options['title'],
                'status' => $options['default_status'],
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ];
        }
        $this->repository->insertData($data);
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\DropOffOptionSubscriber@handleDispensaryCreated'
        );
    }
}
