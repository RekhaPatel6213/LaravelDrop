<?php

namespace App\Notifications\Admin\Dispensary;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Admin\Dispensary\DispensaryUser;
use Illuminate\Support\HtmlString;

class SendPasswordNotification extends Notification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $dispensaryName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(DispensaryUser $user, $dispensaryName)
    {
        $this->user = $user;
        $this->dispensaryName = $dispensaryName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $hubUrl = $this->getUrl('hub');
        $dispatchUrl = $this->getUrl('dispatch');

        return (new MailMessage)
            ->subject('You have been granted access to '.$this->dispensaryName)
            ->greeting('Hello ')
            ->line('Let\'s get you logged into your account')
            ->line('You\'ve been granted access for the Hub and Dispatch')
            ->line('Click the button to get access to your Hub')
            ->line($hubUrl)
            ->line('In the Hub, you will have access to see at a glance how your shop is performing, sales, drops, and customer data.')
            ->line('Click the button to get access to your Dispatch')
            ->line($dispatchUrl)
            ->line('In the Dispatch you will have access to add drivers, assign drops, configure settings, view your drivers, and drops in real time.');
    }

    public function getUrl($type)
    {
        $url  = url(route('dispensary.password.reset', [
            'token' => $this->user->getPasswordToken(),
            'email' => $this->user->email,
        ], false));
        return $line = new HtmlString("<a href='{$url}'>".strtoupper($type)." ACCESS </a>");
    }
}
