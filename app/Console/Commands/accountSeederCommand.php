<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Account\Database\Seeders\AccountGroupSeeder;

class accountSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('db:seed', ['--class' => AccountGroupSeeder::class]);
    }
}
