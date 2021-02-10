<?php

namespace App;

use App\Enums\UserRoleEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRole($roleName): bool
    {
        return $this->role === $roleName;
    }

    public function hasPermission($permissionName): bool
    {
        $permissions = $this->getAttribute('permissions');

        if ($permissions === null) {
            return false;
        }

        return in_array($permissionName, $permissions);
    }

    public function assignPermissions(array $permissions)
    {
        $this->setAttribute('permissions', $permissions);
    }
}
