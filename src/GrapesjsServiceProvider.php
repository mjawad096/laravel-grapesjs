<?php

namespace Dotlogics\Grapesjs;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GrapesjsServiceProvider extends ServiceProvider
{
    protected $routeFilePath = '/routes/laravel-grapesjs.php';
    protected $confiFilePath = 'laravel-grapesjs.php';
    protected $publicDirPath = 'vendor/laravel-grapesjs';
    protected $viewDirPath = 'views/vendor/laravel-grapesjs';
    protected $namespace = 'laravel-grapesjs';

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

        $this->setupViewDirectives();
    }



    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function setupRoutes(Router $router)
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__.$this->routeFilePath;

        // but if there's a file with the same name in routes/backpack, use that one
        if (file_exists($path = base_path($this->routeFilePath))) {
            $routeFilePathInUse = $path;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }

    protected function publishFiles()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path($this->confiFilePath),
        ], [$this->namespace, 'config']);

        $this->publishes([
            __DIR__.'/../dist' => public_path($this->publicDirPath),
        ], [$this->namespace, 'public', 'laravel-assets']);

        $this->publishes([
            __DIR__.'/resources/views' => resource_path($this->viewDirPath),
        ], [$this->namespace, 'views']);
    }

    protected function setupViewDirectives()
    {
        //To Handle error if there no icon defined for any template
        $this->app->singleton('template-icon', function($app){ 
            return new class {
                public function url(){}
            }; 
        });

        Blade::directive('templateIcon', function ($args) {
            $args = Blade::stripParentheses($args);

            return "<?php \$app->singleton('template-icon', function(\$app){ 
                return new class {
                    protected \$called = false;
                
                    public function url() {
                        if(\$this->called) return null;

                        \$this->called = true;

                        return '<img src=\"' . url($args) . '\" style=\"max-width: 100%;max-height: 100%;\" />';
                    }
                };
            }); ?>";
        });
    }
}
