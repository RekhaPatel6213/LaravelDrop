<?php

namespace App\Listeners\Admin\Dispensary;

use App\Events\Admin\Dispensary\UserResetPassword;
use App\Notifications\Admin\Dispensary\UserResetPasswordNotification;

class UserResetPasswordListener
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
     * @param  ResetPassword  $event
     * @return void
     */
    public function handle(UserResetPassword $event)
    {
        $dispensaryUser = $event->dispensaryUser;
        $token = $event->token;
        $dispensaryUser->notify(new UserResetPasswordNotification($token));
    }
}
