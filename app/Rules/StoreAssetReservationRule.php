<?php

namespace App\Rules;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class StoreAssetReservationRule implements Rule
{

    public $start_time;
    public $end_time;
    public $date;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date, $start_time, $end_time)
    {
        $this->date = $date;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $start_time = Carbon::parse($this->start_time);
        $end_time = Carbon::parse($this->end_time);
        $isEmptyAsset = true;
        $record = Reservation::whereBetween('start_time', [$start_time, $end_time])
            ->orWhereBetween('end_time', [$start_time, $end_time])
            ->where($attribute, $value)
        // [masih dipakai jika ada perubahan proses bisnis]
        // ->where('approval_status', ReservationStatusEnum::already_approved())
            ->get();
        if (!count($record)) {
            return true;
        }
        $start_time = $this->convertTime(collect($record)->min('start_time'));
        $end_time = $this->convertTime(collect($record)->max('end_time'));
        $start_time_max = $this->convertTime(collect($record)->max('start_time'));
        $end_time_min = $this->convertTime(collect($record)->min('end_time'));
        $reqStartTime = $this->convertTime($this->start_time);
        $reqEndTime = $this->convertTime($this->end_time);
        if (
            ($reqStartTime <= $start_time && $reqEndTime >= $end_time) ||
            ($reqStartTime >= $start_time && $reqEndTime <= $end_time) ||
            ($reqStartTime == $start_time_max && $reqEndTime == $end_time_min )
        ) {
            $isEmptyAsset = false;
        }
        return $isEmptyAsset;
    }

    public function convertTime($time)
    {
        return $this->decimalHours(Carbon::parse($time)
                ->format('H:i:s'));
    }

    public function decimalHours($time)
    {
        $hms = explode(":", $time);
        return ($hms[0] + ($hms[1] / 60) + ($hms[2] / 3600));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.asset_reserved');
    }
}
