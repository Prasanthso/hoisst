<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('check:margins')->everyThreeHours();// Adjust timing as needed
        $schedule->command('check:rm-price-updates')->hourly();
        $schedule->command('check:pm-price-updates')->everyFiveMinutes();
        $schedule->command('check:pd-price-updates')->everyFiveMinutes();

        $schedule->command('check:rwm-price-threshold')->hourly();
        $schedule->command('check:pm-price-threshold')->everyFiveMinutes();
        $schedule->command('check:pd-price-threshold')->everyFiveMinutes();
        
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
