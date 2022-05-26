<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $token;
    protected $isNew;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $isNew = false)
    {
        $this->token = $token;
        $this->isNew = $isNew;
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
        $url = url(route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        if ($this->isNew) {
            return (new MailMessage)
                ->subject('You have been granted access to Master Dashboard')
                ->greeting('Hello ')
                ->line('You have been granted access to Master Dashboard')
                ->line('Next, use the links to get access', $url)
                ->action('Master Access', $url)
                ->line('Master you will have access to ass client, configure settings, and view your report in real time.');
        }

        return (new MailMessage)
            ->subject('You have been granted access to Master Admin')
            ->greeting('Hello ')
            ->line('Reset your password for Master Dashboard')
            ->line('Next, use the links to get access')
            ->action('RESET PASSWORD', $url)
            ->line('You recently asked to change the password for your Drop master account, associated with this email address. Just click the button to the left to set new password for your account.');
    }
}
