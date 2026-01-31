<?php
namespace Modules\Account\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class AccountServiceProvider  extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function moduleRegister()
    {
        // Define global variables
        $modules = app('modules');
        array_push($modules, [
            'name' => 'Account',
            'description' => 'Account Module',
            'path' => 'Modules/Account',
            'sidebarView' => 'Account::partials._sidebar',
        ]);
        app()->instance('modules', $modules);
        
    }


    public function boot()
    {
        $this->moduleRegister();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'Account');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $this->routes(function () {

            Route::middleware(['web'])
                ->namespace($this->namespace)
                ->group(__DIR__.'/../routes/web.php');

            Route::prefix('api')
                ->as('api.')
                
                ->namespace($this->namespace)
                ->middleware('api')
                ->group(__DIR__.'/../routes/api.php');
        });
    }
}