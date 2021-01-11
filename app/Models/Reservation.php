<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    const NOT_YET_APPROVED = 'NOT_YET_APPROVED';
    const ALREADY_APPROVED = 'ALREADY_APPROVED';
    const REJECTED = 'REJECTED';

    protected $guarded = [];

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
