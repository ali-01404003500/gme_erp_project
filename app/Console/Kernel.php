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
        $schedule->command('attendance:auto-create')->dailyAt('00:10')->timezone('Asia/Dhaka')->withoutOverlapping();
        $schedule->command('bills:generate')->dailyAt('00:11')->timezone('Asia/Dhaka')->withoutOverlapping();
        $schedule->command('sms:send-pending')->everyMinute()->timezone('Asia/Dhaka')->withoutOverlapping();
        
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
