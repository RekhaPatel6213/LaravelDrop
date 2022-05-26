<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Events\Admin\Dispensary\DispensaryHourSetCreated;
use App\Models\Repositories\Admin\Dispensary\DispensaryHourSetRepository;

class DispensaryHourSetSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $repository;
    public function __construct(DispensaryHourSetRepository $repository)
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
        $name = $event->args['name'];
        $data = [
            'dispensary_id' => $dispensary->id,
            'name' => $name,
        ];
        $dispensaryHourSet = $this->repository->create($data);
        event(new DispensaryHourSetCreated($dispensary, $dispensaryHourSet));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\DispensaryHourSetSubscriber@handleDispensaryCreated'
        );
    }
}
