<?php

namespace Vidovic\Rolly;

use Illuminate\Support\ServiceProvider;

class RollyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Register published configuration
        $this->publishes([
            __DIR__.'/config/rolly.php' => config_path('rolly.php'),
        ], 'rolly');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }

    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/rolly.php',
            'rolly'
        );
    }
}
