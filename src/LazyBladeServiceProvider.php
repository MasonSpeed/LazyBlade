<?php

namespace captenmasin\LazyBlade;

use captenmasin\LazyBlade\Providers\BladeServiceProvider;
use captenmasin\LazyBlade\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;

class LazyBladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('lazyblade.php'),
            ], 'config');


            $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lazyblade');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/lazyblade'),
            ], 'views');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerProviders();
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'lazyblade');
    }


    protected function registerProviders()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(BladeServiceProvider::class);
    }
}
