<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Events\Admin\Dispensary\DispensaryUpdated;
use App\Models\Repositories\Admin\Dispensary\DomainRepository;

class DomainSubscriber
{
    protected $domainRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DomainRepository $domainRepository)
    {
        $this->domainRepository = $domainRepository;
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
        $domainName = $event->args['domain'];
        $data = [
                'domain' => $domainName,
                'dispensary_id' => $dispensary->id,
        ];
        $this->domainRepository->create($data);
    }

    /**
     * Handle the event.
     *
     * @param  DispensaryUpdated  $event
     * @return void
     */
    public function handleDispensaryUpdated(DispensaryUpdated $event)
    {
        $dispensary = $event->dispensaryUpdatedObj;
        if (isset($event->args['domain'])) {
            $domainName = $event->args['domain'];
            $dispensary->domains()->where('dispensary_id', $dispensary->id)->update(
                [
                    'domain' => $domainName,
                ]
            );
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\DomainSubscriber@handleDispensaryCreated'
        );

        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryUpdated',
            'App\Subscribers\Admin\Dispensary\DomainSubscriber@handleDispensaryUpdated'
        );
    }
}
