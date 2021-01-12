<?php

namespace App\Rules;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class AssetReservation implements Rule
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
        return Reservation::whereTime('start_time', '>=', Carbon::parse($this->start_time))
            ->whereTime('end_time', '<=', Carbon::parse($this->end_time))
            ->where('date', Carbon::parse($this->date))
            ->where($attribute, $value)->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.exists');
    }
}
