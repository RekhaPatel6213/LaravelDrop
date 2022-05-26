<?php

namespace App\Subscribers\Admin\Dispensary;

use App\Events\Admin\Dispensary\DispensaryCreated;
use Database\Seeders\MailTemplateSeeder;

class EmailTemplateSubscriber
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
        $seeder = new MailTemplateSeeder();
        $seeder->run($dispensary->id);
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\Dispensary\DispensaryCreated',
            'App\Subscribers\Admin\Dispensary\EmailTemplateSubscriber@handleDispensaryCreated'
        );
    }
}
