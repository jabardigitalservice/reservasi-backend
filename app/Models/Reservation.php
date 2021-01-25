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

    public const VALIDATION_RULES = [
        'title' => 'required',
        'asset_id' => [
            'required',
            'exists:assets,id,deleted_at,NULL'
        ],
        'date' => 'required|date|date_format:Y-m-d',
        'start_time' => 'required|date|date_format:Y-m-d H:i',
        'end_time' => 'required|date|date_format:Y-m-d H:i|after:start_time',
    ];

    public function scopeCheckRoleEmployee($query)
    {
        if (Auth::user()->role == UserRoleEnum::employee_reservasi()) {
            return $query->where('user_id_reservation', Auth::user()->uuid);
        }
        return $query;
    }

    public function scopeApprovalStatus($query, $approval_status = null)
    {
        if ($approval_status) {
            return $query->where('approval_status', $approval_status);
        }
        return $query;
    }

    public function scopeActionUpdated($query, $id)
    {
        if ($id) {
            return $query->where('id', '!=', $this->id);
        }
        return $query;
    }

    public function notYetApproved()
    {
        return $this->approval_status != ReservationStatusEnum::not_yet_approved();
    }
}
