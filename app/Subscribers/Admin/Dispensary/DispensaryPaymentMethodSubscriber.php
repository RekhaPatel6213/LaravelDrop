<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Models\Admin\Dispensary\DispensaryPaymentMethod;
use App\Models\Repositories\Admin\Dispensary\DispensaryPaymentMethodRepository;
use Carbon\Carbon;

class DispensaryPaymentMethodSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $repository;
    public function __construct(DispensaryPaymentMethodRepository $repository)
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
        foreach (DispensaryPaymentMethod::PAYMENT_METHODS as $method) {
            $data[] = [
                'dispensary_id' => $dispensary->id,
                'payment_slug' => $method['slug'],
                'payment_title' => $method['title'],
                'status' => $method['default_status'],
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
            'App\Subscribers\Admin\Dispensary\DispensaryPaymentMethodSubscriber@handleDispensaryCreated'
        );
    }
}
