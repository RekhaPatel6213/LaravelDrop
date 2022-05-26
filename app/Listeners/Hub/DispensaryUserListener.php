<?php

namespace App\Listeners\Hub;

use App\Events\Hub\DispensaryUserEvent;
use App\Notifications\Admin\Dispensary\DispensaryUserResetPasswordNotification;

class DispensaryUserListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DispensaryUserEvent $event)
    {
        $dispensaryUser = $event->dispensaryUser;
        $token = $event->dispensaryUser->getPasswordToken();

        $dispensaryUser->notify(new DispensaryUserResetPasswordNotification($dispensaryUser, $token, true));
    }
}
