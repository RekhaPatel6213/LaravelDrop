<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Models\Repositories\Hub\PageRepository;

class LegalSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $repository;
    public function __construct(PageRepository $repository)
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
        $data = [];
        foreach (config('constants.DEFAULT_LEGAL') as $legal) {
            $data = [
                'dispensary_id' => $dispensary->id,
                'name' => $legal['name'],
                'page_code' => $legal['page_code'],
                'group' => $legal['group'],
                'html_content' => $legal['html_content'],
                'priority' => $legal['priority'],
                'sub_id' => $legal['sub_id'],
            ];
            $this->repository->create($data);
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\LegalSubscriber@handleDispensaryCreated'
        );
    }
}
