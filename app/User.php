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

    public function getUser()
    {
        $token = json_decode(Auth::token());

        $filteredRole = UserRoleEnum::employee_reservasi();

        foreach ($token->realm_access->roles as $role) {
            if (in_array($role, UserRoleEnum::getAll())) {
                $filteredRole = $role;
            }
        }

        $data = (object) [
            'id' => $token->sub,
            'name' => $token->name,
            'username' => $token->preferred_username,
            'email' => $token->email,
            'role' => $filteredRole,
        ];

        return $data;
    }
}
