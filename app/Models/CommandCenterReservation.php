<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
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
}
