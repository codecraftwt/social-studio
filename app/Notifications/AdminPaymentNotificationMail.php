<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class AdminPaymentNotificationMail extends Notification
{
    use Queueable, SerializesModels;

    public $username;
    public $amount;
    public $subscriptionType;
    protected string $adminEmail;

    public function __construct(string $adminEmail, $username, $amount, $subscriptionType)
    {
        $this->adminEmail = $adminEmail;
        $this->username = $username;
        $this->amount = $amount;
        $this->subscriptionType = $subscriptionType;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Payment Received')
            ->greeting('Hello Admin!')
            ->line('User ' . $this->username . ' has completed a payment of ' . $this->amount . ' for the ' . $this->subscriptionType . ' subscription.')
            ->line('Please check the details in the admin dashboard.');
            // ->line('Best Regards,')
            // ->line('Your Team');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
