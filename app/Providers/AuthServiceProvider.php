<?php

namespace App\Providers;

use App\Enums\UserRoleEnum;
use App\Models\Reservation;
use App\Policies\ReservationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Reservation::class => ReservationPolicy::class,
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
        Gate::define('isAdmin', function (User $user) {
            return $user->hasRole(UserRoleEnum::admin_reservasi());
        });

        /* define a employee user role */
        Gate::define('isEmployee', function (User $user) {
            return $user->hasRole(UserRoleEnum::employee_reservasi());
        });
    }
}
