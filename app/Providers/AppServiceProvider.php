<?php

namespace App\Providers;

use App\Contracts\PhoenixPharmaClient;
use App\Services\PhoenixPharma\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PhoenixPharmaClient::class, static function ($app) {
            return new Client(
                config('services.distributors.phoenixpharma.username'),
                config('services.distributors.phoenixpharma.password'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
