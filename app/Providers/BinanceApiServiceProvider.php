<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Binance\API as BinanceApi;

class BinanceApiServiceProvider extends ServiceProvider
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
        $this->app->singleton(BinanceApi::class, function ($app) {
            return new BinanceApi(config('binance.key.public'), config('binance.key.secret'));
        });
    }
}
