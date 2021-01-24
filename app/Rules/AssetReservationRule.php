<?php

namespace App\Rules;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;
use Illuminate\Contracts\Validation\Rule;

class AssetReservationRule implements Rule
{

    public $start_time;
    public $end_time;
    public $date;
    public $id;
    public $isAvailable = true;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date, $start_time, $end_time, $id = null)
    {
        $this->date = $date;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
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
        $reservations = Reservation::where($attribute, $value)
            ->where(function ($query) {
                $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $this->end_time]);
            })
            ->approvalStatus(ReservationStatusEnum::already_approved())
            ->actionUpdated($this->id);
        if ($this->isAvailable = $reservations->doesntExist()) {
            return $this->isAvailable;
        } else {
            $this->findComplementSlices($reservations->get());
            return $this->isAvailable;
        }
    }

    /**
     * findComplementSlices
     * find complement set and set of slices
     * @param  mixed $reservations
     * @return void
     */
    public function findComplementSlices($reservations)
    {
        $this->start_time = Reservation::convertTimeToDecimal($this->start_time);
        $this->end_time = Reservation::convertTimeToDecimal($this->end_time);

        foreach ($reservations as $reservation) {
            $complement = (
                $this->start_time <= $reservation->converted_start_time &&
                $this->end_time >= $reservation->converted_end_time
            );
            $slices = (
                $this->start_time >= $reservation->converted_start_time &&
                $this->end_time <= $reservation->converted_end_time
            );
            if ($complement || $slices) {
                $this->isAvailable = false;
                break;
            }
        }
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
