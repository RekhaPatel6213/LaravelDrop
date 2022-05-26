<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Models\Repositories\Hub\DispensaryCategoryRepository;

class DispensaryCategorySubscriber
{
    protected $repository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DispensaryCategoryRepository $repository)
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
        $this->repository->addDefaultCategories($dispensary->id);
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\DispensaryCategorySubscriber@handleDispensaryCreated'
        );
    }
}
