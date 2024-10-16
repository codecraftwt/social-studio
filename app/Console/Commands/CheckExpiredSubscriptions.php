<?php

namespace App\Console\Commands;

use App\Models\TransactionDetail; // Adjust the namespace as needed
use App\Notifications\SubscriptionExpired;
use Illuminate\Console\Command;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expired';
    protected $description = 'Check for expired subscriptions and notify users';

    public function handle()
    {
        $today = now();

        $expiredSubscriptions = TransactionDetail::where('plan_expiry_date', '<', $today)
            ->where('status', 1) 
            ->get();

        foreach ($expiredSubscriptions as $transaction) {
            $user = $transaction->user; // Assuming there's a relation defined
            // $user->notify(new SubscriptionExpired($transaction));
            if (is_null($transaction->expired_email_sent_at)) {
                $user->notify(new SubscriptionExpired($transaction));
                $transaction->expired_email_sent_at = now();
                $transaction->save();
            }
        }

        $this->info('Expired subscriptions checked and notifications sent.');
    }
}

