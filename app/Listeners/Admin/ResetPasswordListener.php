<?php

namespace App\Listeners\Admin;

use App\Events\Admin\ResetPassword;
use App\Notifications\Admin\ResetPasswordNotification;

class ResetPasswordListener
{
    /**
     * Handle the event.
     *
     * @param  ResetPassword  $event
     * @return void
     */
    public function handle(ResetPassword $event)
    {
        $adminUser = $event->adminUser;
        $token = $event->token;
        $adminUser->notify(new ResetPasswordNotification($token));
    }
}
