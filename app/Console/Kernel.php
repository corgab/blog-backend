<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('send:newsletter')->weeklyOn(0, '16:00');
        //$schedule->command('send:newsletter')->daily(); // Testing
        if (App::environment('production')) {
            $schedule->command('publish:post')->dailyAt('10:00');
        } else {
            $schedule->command('publish:post')->everyMinute();
        }

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
