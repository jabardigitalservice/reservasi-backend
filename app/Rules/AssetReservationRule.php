<?php

namespace App\Rules;

use App\Models\Reservation;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;
use stdClass;

class AssetReservationRule implements Rule
{

    public $reservation;
    public $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date, $start_time, $end_time, $id = null)
    {
        $this->reservation = new stdClass();
        $this->reservation->date = $date;
        $this->reservation->start_time = Carbon::parse($start_time);
        $this->reservation->end_time = Carbon::parse($end_time);
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
        return Reservation::where($attribute, $value)
            ->validateTime($this->reservation)
            ->alreadyApproved()
            ->where(function ($query) {
                if ($this->id) {
                    $query->where('id', '!=', $this->id);
                }
            })->doesntExist();
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
