<?php

namespace App\Rules;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class UpdateAssetReservationRule implements Rule
{

    public $start_time;
    public $end_time;
    public $date;
    public $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date, $start_time, $end_time, $id)
    {
        $this->date = $date;
        $this->start_time = Carbon::parse($start_time);
        $this->end_time = Carbon::parse($end_time);
        $this->id = $id;
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
        $isAvailable = true;
        $reservations = Reservation::where($attribute, $value)
            ->where(function ($query) {
                $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $this->end_time]);
            })
            ->where('id', '!=', $this->id)
            ->where('approval_status', ReservationStatusEnum::already_approved())
            ->get();
        if (!count($reservations)) {
            return true;
        }

        $requestStartTime = Reservation::convertTime($this->start_time);
        $requestEndTime = Reservation::convertTime($this->end_time);

        //find complement set and set of slices
        foreach ($reservations as $reservedAsset) {
            $complement = (
                $requestStartTime <= $reservedAsset->converted_start_time &&
                $requestEndTime >= $reservedAsset->converted_end_time
            );
            $slices = (
                $requestStartTime >= $reservedAsset->converted_start_time &&
                $requestEndTime <= $reservedAsset->converted_end_time
            );
            if ($complement || $slices) {
                $isAvailable = false;
                break;
            }
        }
        return $isAvailable;
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
