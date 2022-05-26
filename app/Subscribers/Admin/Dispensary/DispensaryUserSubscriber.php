<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Events\Admin\Dispensary\DispensaryUpdated;
use App\Models\Admin\Dispensary\DispensaryUser;
use App\Models\Repositories\Admin\Dispensary\DispensaryUserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DispensaryUserSubscriber
{
    protected $repository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DispensaryUserRepository $repository)
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
        $request = $event->args;
        $data = [
            'dispensary_id' => $dispensary->id,
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['contact_email'],
            'phone' => $request['contact_phone'],
            'password' => Hash::make(Str::random(32)),
            'is_owner' => DispensaryUser::YES,
        ];

        $dispensaryUser = $this->repository->create($data);
        $this->repository->setPermission($dispensaryUser);
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
        $args = $event->args;
        $columns = ['first_name' => 'first_name', 'last_name' => 'first_name', 'email' => 'contact_email', 'phone' => 'contact_phone'];
        $updateData = [];
        foreach ($columns as $key => $value) {
            if (isset($args[$value])) {
                $updateData[$key] = $args[$value];
            }
        }
        if (!empty($updateData)) {
            $dispensary->dispensaryUser()->where('dispensary_id', $dispensary->id)->update(
                [
                    'first_name' => $args['first_name'],
                    'last_name' => $args['last_name'],
                    'email' => $args['contact_email'],
                    'phone' => $args['contact_phone'],
                ]
            );
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\DispensaryUserSubscriber@handleDispensaryCreated'
        );

        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryUpdated',
            'App\Subscribers\Admin\Dispensary\DispensaryUserSubscriber@handleDispensaryUpdated'
        );
    }
}
