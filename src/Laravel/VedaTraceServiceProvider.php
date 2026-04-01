<?php

namespace VedaTrace\Laravel;

use Illuminate\Support\ServiceProvider;
use VedaTrace\VedaTrace;

class VedaTraceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/vedatrace.php', 'vedatrace');

        $this->app->singleton('vedatrace', function ($app) {
            $config = $app['config']->get('vedatrace');
            return VedaTrace::create($config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $configPath = function_exists('config_path') 
                ? config_path('vedatrace.php') 
                : $this->app->basePath('config/vedatrace.php');

            $this->publishes([
                __DIR__ . '/../../config/vedatrace.php' => $configPath,
            ], 'vedatrace-config');
        }
    }
}
