<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeoLocationSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Geo Location Seeder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('db:seed', ['--class' => 'GeoLocationsCsvSeeder']);

        $this->info('Geo Location tables seeded successfully!');
    }
}
