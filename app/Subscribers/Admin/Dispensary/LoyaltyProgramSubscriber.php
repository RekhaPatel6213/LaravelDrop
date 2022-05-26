<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Models\Admin\Dispensary\LoyaltyProgram;
use App\Models\Repositories\Admin\Dispensary\LoyaltyProgramRepository;
use Carbon\Carbon;

class LoyaltyProgramSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $repository;
    public function __construct(LoyaltyProgramRepository $repository)
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
        foreach (LoyaltyProgram::DEFAULT_PROGRAMS as $program) {
            $data[] = [
                'dispensary_id' => $dispensary->id,
                'name' => $program['name'],
                'type' => $program['type'],
                'points' => $program['points'],
                'status' => $program['default_status'],
                'is_default' => true,
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
            'App\Subscribers\Admin\Dispensary\LoyaltyProgramSubscriber@handleDispensaryCreated'
        );
    }
}
