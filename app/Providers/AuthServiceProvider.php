<?php

namespace App\Providers;

use App\Enums\UserRoleEnum;
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
        Gate::define('isAdmin', function ($user) {
            return $user->role == UserRoleEnum::ADMIN();
        });

        /* define a employee user role */
        Gate::define('isEmployee', function ($user) {
            return $user->role == UserRoleEnum::EMPLOYEE();
        });
    }
}
