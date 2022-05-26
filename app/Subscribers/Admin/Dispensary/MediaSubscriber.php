<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Events\Admin\Dispensary\DispensaryUpdated;
use App\Events\Driver\DriverUserCreated;
use App\Events\Driver\DriverUserUpdated;

class MediaSubscriber
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
     * @param  DispensaryCreated  $event
     * @return void
     */
    public function handleDispensaryCreated(DispensaryCreated $event)
    {
        $dispensary = $event->dispensary;
        $args = $event->args;
        $mediaCollection = [
            'logoObj' => 'logos',
            'headerLogoObj' => 'header_logos',
            'appIconObj' => 'app_icons',
        ];
        foreach ($mediaCollection as $key=>$value) {
            if (isset($args[$key])) {
                $dispensary->addMedia($args[$key])->toMediaCollection($value);
            }
        }

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
        $mediaCollection = [
            'logoObj' => 'logos',
            'headerLogoObj' => 'header_logos',
            'appIconObj' => 'app_icon',
        ];
        foreach ($mediaCollection as $key => $value) {
            if (isset($args[$key])) {
                $dispensary->addMedia($args[$key])->toMediaCollection($value);
            }
        }
    }


    /**
     * Handle the event.
     *
     * @param  DispensaryCreated  $event
     * @return void
     */
    public function handleDriverUserCreated(DriverUserCreated $event)
    {
        $driver = $event->driver;
        $args = $event->args;
        if (isset($args['profileImageObj'])) {
            $driver->addMedia($args['profileImageObj'])->toMediaCollection('profile_images');
        }
    }

    /**
     * Handle the event.
     *
     * @param  DispensaryUpdated  $event
     * @return void
     */
    public function handleDriverUserUpdated(DriverUserUpdated $event)
    {
        $driver = $event->driverUpdatedObj;
        $args = $event->args;
        if (isset($args['profileImageObj'])) {
            $driver->addMedia($args['profileImageObj'])->toMediaCollection('profile_images');
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\MediaSubscriber@handleDispensaryCreated'
        );

        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryUpdated',
            'App\Subscribers\Admin\Dispensary\MediaSubscriber@handleDispensaryUpdated'
        );

        $events->listen(
            'App\Events\Driver\DriverUserCreated',
            'App\Subscribers\Admin\Dispensary\MediaSubscriber@handleDriverUserCreated'
        );

        $events->listen(
            'App\Events\Driver\DriverUserUpdated',
            'App\Subscribers\Admin\Dispensary\MediaSubscriber@handleDriverUserUpdated'
        );
    }
}
