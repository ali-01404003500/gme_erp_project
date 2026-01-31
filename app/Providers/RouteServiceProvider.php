<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Define the rate limiter for API requests. This limits the number of API
        // requests a user can make in a minute. The rate limiter uses the user's
        // ID if they are authenticated, otherwise it uses their IP address.
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            if (file_exists(base_path('Modules/Task/routes/web.php'))) {
                Route::middleware('web')
                    ->namespace('Modules\Task\Http\Controllers')
                    ->group(base_path('Modules/Task/routes/web.php'));
            }
            
            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/dashboard.php'));

            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/changelog.php'));

            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/application.php'));

            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/uielement.php'));

            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/feature.php'));

            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/pages.php'));
            
            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/customer.php'));
        });
    }
}
