<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionEndingSoon extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $expiryDate = \Carbon\Carbon::parse($this->transaction->plan_expiry_date);
    
        return (new MailMessage)
            ->subject('Your Subscription is Ending Soon')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('Your subscription for ' . $this->transaction->subscription_type . ' will expire on ' . $expiryDate->format('Y-m-d') . '. Please consider renewing it.')
            ->action('Renew Subscription', url('/renew'))
            ->line('Thank you for being with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
