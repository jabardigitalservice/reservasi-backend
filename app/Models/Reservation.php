<?php

namespace App\Models;

use App\Enums\ReservationStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Spatie\Enum\Laravel\HasEnums;

class Reservation extends Model
{
    use SoftDeletes;
    use HasEnums;

    protected $fillable = [
        'user_id_reservation',
        'user_fullname',
        'username',
        'title',
        'email',
        'description',
        'asset_id',
        'asset_name',
        'asset_description',
        'approval_status',
        'note',
        'date',
        'start_time',
        'end_time',
        'user_id_updated'
    ];

    protected $dates = [
        'start_time',
        'end_time',
        'approval_date'
    ];

    protected $enums = [
        'approval_status' => ReservationStatusEnum::class,
    ];

    protected $casts = [
        'start_time' => 'datetime:Y-m-d H:i',
        'end_time' => 'datetime:Y-m-d H:i',
    ];

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

    public function getCheckTimeEditValidAttribute()
    {
        return Carbon::now('+07:00') > $this->start_time->subMinutes(30);
    }
}
