<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'end_time'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getSomeDateAttribute($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
