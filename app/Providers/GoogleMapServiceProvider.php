<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GoogleMapService;

class GoogleMapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\GoogleMapService', function ($app) {
            return new GoogleMapService(config('services.googlemap.key'));
        });
    }
}
