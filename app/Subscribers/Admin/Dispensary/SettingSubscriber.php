<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Models\Admin\Dispensary\Dispensary;
//use Spatie\LaravelSettings\Migrations\SettingsMigrator;
use Stancl\Tenancy\Tenancy;

class SettingSubscriber
{
    /**
     * Handle the event.
     *
     * @param  DispensaryCreated  $event
     * @return void
     */
    public function handleDispensaryCreated(DispensaryCreated $event)
    {
        tenancy()->initialize($event->dispensary);
        $aSeeder = new \Database\Seeders\DispensarySeeder();
        $aSeeder->run();
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\SettingSubscriber@handleDispensaryCreated'
        );
    }
}
