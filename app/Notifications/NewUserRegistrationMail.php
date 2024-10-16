<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistrationMail extends Notification
{
    use Queueable;

    protected string $adminEmail;
    protected string $username;

    public function __construct(string $adminEmail, string $username)
    {
        $this->adminEmail = $adminEmail;
        $this->username = $username;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New User Registration')
            ->greeting('Hello Admin!')
            ->line('A new user has registered with the username: ' . $this->username)
            ->line('You can view their profile on the admin dashboard.');
            // ->line('Best Regards,')
            // ->line('Walstar Poster');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
