<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessAutoAttendance;

class AutoCreateAttendance extends Command
{
    protected $signature = 'attendance:auto-create';
    protected $description = 'Dispatch a queued job to create attendance records for all employees';

    public function handle()
    {
        ProcessAutoAttendance::dispatch();
        $this->info('Attendance job has been dispatched to the queue.');
    }
}