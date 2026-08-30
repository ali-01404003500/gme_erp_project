<?php

namespace Modules\Import\Providers;

use Illuminate\Support\ServiceProvider;

class ImportServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        $this->moduleRegister();

        // Views
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'Import'
        );

        // Migrations
        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        // Routes
        $this->loadRoutesFrom(
            __DIR__ . '/../routes/web.php'
        );
    }

    /**
     * Register module in global modules array.
     */
    public function moduleRegister(): void
    {
        $modules = app('modules');

        // Prevent duplicate module registration
        $exists = collect($modules)->contains(function ($module) {
            return ($module['name'] ?? null) === 'Import';
        });

        if (! $exists) {
            $modules[] = [
                'name' => 'Import',
                'description' => 'Import Module',
                'path' => 'Modules/Import',
                'sidebarView' => 'Import::partials._sidebar',
            ];

            app()->instance('modules', $modules);
        }
    }
}