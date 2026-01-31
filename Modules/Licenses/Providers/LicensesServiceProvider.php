<?php
namespace Modules\Licenses\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class LicensesServiceProvider  extends ServiceProvider
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
            'name' => 'Licenses',
            'description' => 'Licenses Module',
            'path' => 'Modules/Licenses',
            'sidebarView' => 'Licenses::partials._sidebar',
        ]);
        app()->instance('modules', $modules);
        
    }


    public function boot()
    {
        $this->moduleRegister();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'Licenses');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $this->routes(function () {

            Route::middleware(['web'])
                ->namespace($this->namespace)
                ->group(__DIR__.'/../routes/web.php');

            Route::prefix('api')
                ->namespace($this->namespace)
                ->middleware('api')
                ->group(__DIR__.'/../routes/api.php');
        });
    }
}