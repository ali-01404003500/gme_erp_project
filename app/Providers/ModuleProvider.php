<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleProvider extends ServiceProvider
{
    private $modules = [];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('modules', function () {
            return $this->modules;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
