<?php 

namespace Rafiki23\WatermarkCredits;

use Illuminate\Support\ServiceProvider;

class WatermarkServiceProvider extends ServiceProvider
{
    /**
     * Boot services.
     */
    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/../config/watermark.php' => config_path('watermark.php'),
        ], 'config');
    }

    /**
     * Register services.
     */
    public function register()
    {
        // Register the service the package provides.
        $this->app->singleton('watermark', function ($app) {
            return new Watermark();
        });
    }
}
