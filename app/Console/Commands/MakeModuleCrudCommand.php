<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeModuleCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:crud {name} {module}';

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
        //

        $name = $this->argument('name');
        $module = $this->argument('module');
        if(empty($module)){
            //ask for module name
            $module = $this->ask('Enter module name')??null;
        }

        // make module
        $this->call('module:model', [
            'name' => $name,
            'module' => $module
        ]);

        $this->info('model created successfully');

        //service name
        $this->call('module:service', [
            'name' => $name,
            'module' => $module
        ]);

        $this->info('service created successfully');

        $this->call('module:controller', [
            'name' => $name,
            'module' => $module
        ]);

        $this->info('controller created successfully');
    }
}
