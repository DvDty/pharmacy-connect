<?php

namespace App\Providers;

use App\Services\PhoenixPharma\Client as PhoenixPharmaClient;
use Illuminate\Support\Facades\Schema;
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
            return new PhoenixPharmaClient(
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
        Schema::defaultStringLength(191);
    }
}
