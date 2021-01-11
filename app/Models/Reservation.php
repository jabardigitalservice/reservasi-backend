<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use SoftDeletes;

    protected $dates = [
        'reservation_start',
        'reservation_end',
        'created_at',
        'updated_at',
    ];

    public function getSomeDateAttribute($date)
    {
        return $date->format('Y M D H:i:s');
    }
}
