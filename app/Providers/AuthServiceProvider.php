<?php

namespace Cryptounity\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


use Cryptounity\User;

use Cryptounity\Stacking;
use Cryptounity\Policies\StackingPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Cryptounity\Model' => 'Cryptounity\Policies\ModelPolicy',
        Stakcing::class => StackingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('create-stacking',function($user) {
            
        });
        //
    }
}
