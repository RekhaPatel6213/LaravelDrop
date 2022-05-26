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

class DispensaryUserResetPasswordNotification extends Notification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $dispensaryUser;
    protected $token;
    protected $role;
    protected $email;
    protected $isNew;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(DispensaryUser $dispensaryUser, $token, $isNew = false)
    {
        $this->dispensaryUser = $dispensaryUser;
        $this->token = $token;
        $this->role =  $this->dispensaryUser->role;
        $this->email =  $this->dispensaryUser->email;
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
        $hubUrl = $this->getUrl('hub');
        $dispatchUrl = $this->getUrl('dispatch');

        $mailMessage = (new MailMessage)
            ->subject('You have been granted access to '.tenant('name'))
            ->greeting('Hello ')
            ->line('Let\'s get you logged into your account')
            ->line('You\'ve been granted access for the following:');

        if ($this->role === DispensaryUser::ALL) {
            $line = new HtmlString("<u><li>".DispensaryUser::HUB."</li><li>".DispensaryUser::DISPATCH."</li>");
        } else {
            $line = new HtmlString("<u><li>".$this->role."</li>");
        }

        $mailMessage->line($line);

        if(in_array($this->role, array(DispensaryUser::HUB, DispensaryUser::ALL))) {
            $mailMessage->line('Click the button to get access to your Hub');
            $mailMessage->line('Click the link below to create your password');
            $mailMessage->line(new HtmlString("<b>Username: </b> <a href='{$this->email}'>".$this->email."</a>"));
            $mailMessage->line($hubUrl);
            $mailMessage->line('In the Hub, you will have access to see at a glance how your shop is performing, sales, drops, and customer data.');
        }

        if(in_array($this->role, array(DispensaryUser::DISPATCH, DispensaryUser::ALL))) {
            $mailMessage->line('Click the button to get access to your Dispatch');
            $mailMessage->line('Click the link below to create your password');
            $mailMessage->line(new HtmlString("<b>Username: </b> <a href='{$this->email}'>".$this->email."</a>"));
            $mailMessage->line($dispatchUrl);
            $mailMessage->line('In the Dispatch you will have access to add drivers, assign drops, configure settings, view your drivers, and drops in real time.');
        }
        return $mailMessage;
    }

    public function getUrl($type)
    {
        $url  = url(route('dispensary.password.reset', [
            'token' => $this->token,
            'email' => $this->email,
        ], false));
        return $line = new HtmlString("<a href='{$url}'>".strtoupper($type)." ACCESS </a>");
    }
}
