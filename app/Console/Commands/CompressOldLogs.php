<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class CompressOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:compress-old-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compress log files from previous year into gzip format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $logPath = storage_path('logs');
        $currentYear = Carbon::now()->year;

        $this->info("Compressing logs older than $currentYear...");

        // Get all log files
        $files = File::files($logPath);


        // Loop through all log files
        foreach ($files as $file) {
            // Get the file name
            $fileName = $file->getFilename();

            //like laravel-2024-05-12.log
            if (preg_match('/^laravel-(\d{4})-(\d{2})-(\d{2})\.log$/', $fileName, $matches)) {
                $year = $matches[1];
                $month = $matches[2];
                $day = $matches[3];

                // check if it is older than current month
                if ($year < $currentYear || ($year == $currentYear && $month < Carbon::now()->month)) {
                     $gzipPath = $file->getPathname() . '.gz';
                     // Skip if already compressed
                    if (File::exists($gzipPath)) {
                        $this->line("Already compressed: $fileName");
                        continue;
                    }

                    // Compress file
                    $this->line("Compressing: $fileName");
                    $contents = File::get($file->getPathname());
                    file_put_contents($gzipPath, gzencode($contents, 9));

                     // Delete the original log file
                    File::delete($file->getPathname());

                }
            }
        }
    }
}
