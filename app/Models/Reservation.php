<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $dates = [
        'reservation_start',
        'reservation_end',
        'created_at',
        'updated_at'
    ];
}
