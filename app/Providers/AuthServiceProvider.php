<?php

namespace App\Providers;

use App\Enums\UserRoleEnum;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /* define a admin user role */
        Gate::define('isAdmin', function () {
            return User::getUser()->role == UserRoleEnum::admin_reservasi();
        });

        /* define a employee user role */
        Gate::define('isEmployee', function () {
            return User::getUser()->role == UserRoleEnum::employee_reservasi();
        });
    }
}
