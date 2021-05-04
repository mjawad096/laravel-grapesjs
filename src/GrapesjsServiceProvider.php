<?php

namespace Topdot\Grapesjs;

use Illuminate\Support\ServiceProvider;

class GrapesjsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'grapesjs');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('grapesjs.php'),
            ], 'config');

            $this->publishes([__DIR__.'/../public' => public_path()], 'public');
        }

        $this->loadViewsFrom(realpath(__DIR__.'/resources/views/'), 'grapesjs');
    }
}
