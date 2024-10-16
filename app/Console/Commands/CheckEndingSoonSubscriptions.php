<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use App\Notifications\SubscriptionEndingSoon;

class CheckEndingSoonSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-ending-soon';
    protected $description = 'Check for subscriptions that are ending soon and send notifications';

    public function handle()
    {
        $today = Carbon::now();
        $endingSoon = TransactionDetail::where('plan_expiry_date', '>', $today)
            ->where('plan_expiry_date', '<=', $today->copy()->addDays(2))
            ->where('status', 1) // Assuming 1 means active
            ->get();

        foreach ($endingSoon as $transaction) {
            $user = $transaction->user; // Ensure this relationship exists
            if ($user) {
                // $user->notify(new SubscriptionEndingSoon($transaction));
                if (is_null($transaction->expiry_soon_email_sent_at)) {
                    $user->notify(new SubscriptionEndingSoon($transaction));
                    $transaction->expiry_soon_email_sent_at = now();
                    $transaction->save();
                }
            }
        }

        $this->info('Ending soon subscriptions checked and notifications sent.');
    }
}
