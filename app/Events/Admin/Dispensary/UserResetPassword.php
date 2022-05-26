<?php

namespace App\Events\Admin\Dispensary;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Admin\Dispensary\DispensaryUser;

class UserResetPassword
{
    public DispensaryUser $dispensaryUser;
    public $token;
    /**
     * Create a dispensary user event instance.
     *
     * @return void
     */
    public function __construct(DispensaryUser $dispensaryUser, $token)
    {
        $this->dispensaryUser = $dispensaryUser;
        $this->token = $token;
    }
}
