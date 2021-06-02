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

        $this->publishes([
            __DIR__.'/public' => public_path(),
            // __DIR__.'/../fonts' => public_path('fonts'),
        ], 'public');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views'),
        ], 'views');
    }
}
