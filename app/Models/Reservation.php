<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'reservation_start',
        'reservation_end',
        'created_at',
        'updated_at',
    ];

    public function getSomeDateAttribute($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
