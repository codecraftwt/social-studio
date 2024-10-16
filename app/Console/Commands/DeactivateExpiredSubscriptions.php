<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransactionDetail;
use Carbon\Carbon;

class DeactivateExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:deactivate-expired-subscriptions';
    protected $signature = 'subscriptions:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Deactivate expired subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $now = Carbon::now();

        // Find subscriptions that have expired
        $expiredSubscriptions = TransactionDetail::where('status', 1) // Assuming 1 means active
            ->where('plan_expiry_date', '<', $now)
            ->get();

        // Deactivate each expired subscription
        foreach ($expiredSubscriptions as $subscription) {
            $subscription->status = 0; // Set status to inactive
            $subscription->save();

            $this->info('Deactivated subscription for user ID: ' . $subscription->user_id);
        }

        if ($expiredSubscriptions->isEmpty()) {
            $this->info('No expired subscriptions found.');
        }
    }
}
