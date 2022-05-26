<?php

namespace App\Listeners\Admin;

use App\Events\Admin\AdminUserCreated;
use App\Notifications\Admin\ResetPasswordNotification;
use App\Notifications\Admin\adminAccessNotification;

class AdminUserCreatedListener
{
    /**
     * Handle the event.
     *
     * @param  AdminUserCreated  $event
     * @return void
     */
    public function handle(AdminUserCreated $event)
    {
        $adminUser = $event->adminUser;
        $token = $event->adminUser->getPasswordToken();
        $adminUser->notify(new ResetPasswordNotification($token, true));
    }
}
