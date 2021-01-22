<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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

    protected $appends = [
        'converted_start_time',
        'converted_end_time',
    ];

    public function getConvertedStartTimeAttribute()
    {
        return self::convertTime($this->start_time);
    }

    public function getConvertedEndTimeAttribute()
    {
        return self::convertTime($this->end_time);
    }

    static function convertTime($time)
    {
        return self::decimalHours(Carbon::parse($time)
                ->format('H:i:s'));
    }

    static function decimalHours($time)
    {
        $hms = explode(':', $time);
        return ($hms[0] + ($hms[1] / 60) + ($hms[2] / 3600));
    }
}
