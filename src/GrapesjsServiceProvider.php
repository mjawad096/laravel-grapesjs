<?php

namespace Dotlogics\Grapesjs;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class GrapesjsServiceProvider extends ServiceProvider
{
    public $routeFilePath = '/routes/laravel-grapesjs.php';
    public $confiFilePath = 'laravel-grapesjs.php';
    public $publicDirPath = 'vendor/laravel-grapesjs';
    public $viewDirPath = 'views/vendor/laravel-grapesjs';
    public $namespace = 'laravel-grapesjs';

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
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views/'), $this->namespace);
        $this->mergeConfigFrom(__DIR__.'/config.php', $this->namespace);

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
        if (file_exists($path = base_path($this->routeFilePath))) {
            $routeFilePathInUse = $path;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }

    public function publishFiles()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path($this->confiFilePath),
        ], [$this->namespace, 'config']);

        $this->publishes([
            __DIR__.'/../dist' => public_path($this->publicDirPath),
        ], [$this->namespace, 'public']);

        $this->publishes([
            __DIR__.'/resources/views' => resource_path($this->viewDirPath),
        ], [$this->namespace, 'views']);
    }
}
