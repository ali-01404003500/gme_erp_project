<?php

namespace App\Providers;

use App\Observers\OrderObserver;
use App\Observers\SalesOrderEmployeeSplitObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Modules\Sales\Models\SalesOrder;
use Modules\SalesForce\Providers\SalesForceServiceProvider;
use Modules\SalesTarget\Models\SalesOrderEmployeeSplit;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        // $this->app->singleton(SalesForceServiceProvider::class, function ($app) {
        //     return new SalesForceServiceProvider($app);
        // });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        SalesOrder::observe(OrderObserver::class); 
        SalesOrderEmployeeSplit::observe(SalesOrderEmployeeSplitObserver::class);

        /** Eita customer der k invoice link share korar link */
        RateLimiter::for('invoice-share', function (Request $request) {
            return [
                Limit::perMinute(20)->by($request->ip()),
                Limit::perMinute(10)->by('token:' . $request->route('token')),
            ];
        });
    }
}
