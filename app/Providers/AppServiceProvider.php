<?php

namespace Cryptounity\Providers;

use Illuminate\Support\ServiceProvider;
use Cryptounity\Service\NxccWallet;
use Cryptounity\Service\NxccApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NxccWallet::class, function($app) {

            return new NxccWallet();
            
        });
        $this->app->singleton(NxccApiService::class, function($app) {

            return new NxccApiService();
            
        });
    }
}
