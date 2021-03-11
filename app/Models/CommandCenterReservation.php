<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Enums\CommandCenterReservationStatusEnum;
use Spatie\Enum\Laravel\HasEnums;

class CommandCenterReservation extends Model
{
    use SoftDeletes;
    use HasEnums;

    protected $fillable = [
        'user_id_resercation',
        'name',
        'reservation_code',
        'nik',
        'organization',
        'organization_address',
        'phone_number',
        'email',
        'purpose',
        'participant',
        'reservation_date',
        'shift',
        'approval_status',
        'notes',
        'approval_date',
        'user_id_updated',
        'user_id_deleted'
    ];

    protected $dates = [
        'reservation_date',
        'approval_date'
    ];

    protected $casts = [
        'reservation_date' => 'datetime',
    ];

    protected $enums = [
        'approval_status' => CommandCenterReservationStatusEnum::class . ':nullable'
    ];

    public function scopeByUser($query, $user)
    {
        return $query->where('user_id_reservation', $user->uuid);
    }

    public function scopeNotYetApproved($query)
    {
        return $query->where('approval_status', CommandCenterReservationStatusEnum::not_yet_approved());
    }

    public function scopeAlreadyApproved($query)
    {
        return $query->where('approval_status', CommandCenterReservationStatusEnum::already_approved());
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', CommandCenterReservationStatusEnum::rejected());
    }

    public function getIsNotYetApprovedAttribute()
    {
        return $this->approval_status != CommandCenterReservationStatusEnum::not_yet_approved();
    }

    public function getHasAlreadyApprovedAttribute()
    {
        return $this->approval_status == CommandCenterReservationStatusEnum::already_approved();
    }

    public function getHasRejectedAttribute()
    {
        return $this->approval_status == CommandCenterReservationStatusEnum::rejected();
    }
}
