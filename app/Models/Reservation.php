<?php

namespace App\Models;

use App\Enums\ReservationStatusEnum;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Reservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id_reservation',
        'user_fullname',
        'username',
        'title',
        'description',
        'asset_id',
        'asset_name',
        'asset_description',
        'approval_status',
        'date',
        'start_time',
        'end_time',
        'user_id_updated'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'start_time',
        'end_time',
        'approval_date'
    ];

    public function scopeCheckRoleEmployee($query)
    {
        if (Auth::user()->role == UserRoleEnum::employee_reservasi()) {
            return $query->where('user_id_reservation', Auth::user()->uuid);
        }
        return $query;
    }

    public function scopeByUser($query, $user)
    {
        return $query->where('user_id_reservation', $user->uuid);
    }

    public function scopeNotYetApproved($query)
    {
        return $query->where('approval_status', ReservationStatusEnum::not_yet_approved());
    }

    public function scopeAlreadyApproved($query)
    {
        return $query->where('approval_status', ReservationStatusEnum::already_approved());
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', ReservationStatusEnum::rejected());
    }

    public function getIsNotYetApprovedAttribute()
    {
        return $this->approval_status != ReservationStatusEnum::not_yet_approved();
    }
}
