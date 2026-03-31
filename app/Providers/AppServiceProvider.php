<?php

namespace App\Providers;

use App\Models\AccessControl\CompanyInfo;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Modules\SalesForce\Providers\SalesForceServiceProvider;

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

        $companyInfo = cache()->remember('company_info', now()->addHours(24), function () { 
            return CompanyInfo::first();
        });

        View::share('companyInfo', $companyInfo);
        
    }
}
