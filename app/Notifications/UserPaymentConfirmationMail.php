<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPaymentConfirmationMail extends Notification
{
    use Queueable;

    public $amount;
    public $subscriptionType;

    public function __construct($amount, $subscriptionType)
    {
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
            ->subject('Payment Confirmation')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for your payment of ' . $this->amount . ' for the ' . $this->subscriptionType . ' subscription.')
            ->line('We will review your payment and approve it shortly.');
            // ->line('Best Regards,')
            // ->line('Walstar');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
