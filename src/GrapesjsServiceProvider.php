<?php

namespace Topdot\Grapesjs;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class GrapesjsServiceProvider extends ServiceProvider
{
    public $routeFilePath = '/routes/grapesjs.php';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views/'), 'grapesjs');
        $this->mergeConfigFrom(__DIR__.'/config.php', 'grapesjs');

        $this->setupRoutes($this->app->router);

        if ($this->app->runningInConsole()) {
            $this->publishFiles();            
        }
    }



    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__.$this->routeFilePath;

        // but if there's a file with the same name in routes/backpack, use that one
        if (file_exists(base_path().$this->routeFilePath)) {
            $routeFilePathInUse = base_path().$this->routeFilePath;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }

    public function publishFiles()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('grapesjs.php'),
        ], 'config');

        $this->publishes([__DIR__.'/public' => public_path()], 'public');

        if (! class_exists('CreateTempMediaTable')) {
            $this->publishes([
                __DIR__.'/database/migrations/create_temp_media_table.php.stub' => database_path('migrations/2021_05_06_064425_create_temp_media_table.php'),
            ], 'migrations');
        }

        if (! class_exists('CreateMediaTable')) {
            $stub_file = "/database/migrations/create_media_table.php.stub";

            $stub_path = __DIR__.'/../../../spatie/laravel-medialibrary';
            if(!file_exists($stub_path.$stub_file)){
                $stub_path = __DIR__;
            }

            $this->publishes([
                $stub_path.$stub_file => database_path('migrations/2021_05_06_064425_create_media_table.php'),
            ], 'migrations');
        }
    }
}
