<?php

namespace Cryptounity\Providers;

use Illuminate\Support\ServiceProvider;
use Cryptounity\Service\WalletService;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WalletService::class, function($app) {
            return new WalletService();
        });
    }
}
