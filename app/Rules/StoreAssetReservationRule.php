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
        $isEmptyAsset = false;
        $record = Reservation::whereBetween('start_time', [$start_time, $end_time])
            ->orWhereBetween('end_time', [$start_time, $end_time])
            ->where($attribute, $value)
        // ->where('approval_status', ReservationStatusEnum::already_approved())
            ->get();
        if (!count($record)) {
            return true;
        }
        $start_time = $this->decimalHours(Carbon::parse(collect($record)->min('start_time'))
                ->format('H:i:s'));
        $end_time = $this->decimalHours(Carbon::parse(collect($record)->max('end_time'))
                ->format('H:i:s'));
        $reqStartTime = $this->decimalHours(Carbon::parse($this->start_time)
                ->format('H:i:s'));
        $reqEndTime = $this->decimalHours(Carbon::parse($this->end_time)
                ->format('H:i:s'));
        if (($start_time > $reqStartTime && $reqStartTime < $end_time) || ($end_time > $reqEndTime && $reqEndTime < $end_time)) {
            $isEmptyAsset = true;
        }
        dd('Req: ' . $reqStartTime . ' s/d ' . $reqEndTime . ' DB: ' . $start_time . ' s/d ' . $end_time);
        // dd($isEmptyAsset);
        return $isEmptyAsset;
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
        return __('validation.asset_booked');
    }
}
