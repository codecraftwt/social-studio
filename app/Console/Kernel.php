<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('subscriptions:check-expired')->everyMinute();
        // $schedule->command('subscriptions:check-ending-soon')->daily();
        $schedule->command('subscriptions:check-expired')->everyMinute()->appendOutputTo(storage_path('logs/subscription_expired.log'));
        $schedule->command('subscriptions:check-ending-soon')->everyMinute()->appendOutputTo(storage_path('logs/subscription_ending_soon.log'));
        $schedule->command('subscriptions:deactivate-expired')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
