<?php

namespace App\Notifications\Admin;

use App\Mail\DispensaryWelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $dispensary;

    /**
     * Create a new notification instance.
     * @param mixed $user
     * @param mixed $dispensary
     * @return void
     */
    public function __construct($user, $dispensary)
    {
        $this->user = $user;
        $this->dispensary = $dispensary;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $mail = new DispensaryWelcomeMail($this->user, $this->dispensary);
        $mail->to($notifiable->email);
        return $mail;
    }
}
