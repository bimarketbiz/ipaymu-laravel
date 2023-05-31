<?php

namespace Marketbiz\IPaymuLaravel;

use Illuminate\Support\ServiceProvider;
use Marketbiz\IPaymuLaravel\IPaymu;

class IPaymuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('ipaymu',function(){

            return new IPaymu();

        });

        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->publishes([
            __DIR__.'/../../config/ipaymu.php' => config_path('ipaymu.php'),
        ], 'marketbiz-ipaymu-config');
    }
}
