<?php

namespace App\Rules;

use App\Models\Reservation;
use Illuminate\Contracts\Validation\Rule;

class AssetReservation implements Rule
{

    public $reservation_start;
    public $reservation_end;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($reservation_start, $reservation_end)
    {
        $this->reservation_start = $reservation_start;
        $this->reservation_end = $reservation_end;
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
        $reservation = Reservation::whereDate('reservation_start', '>=', $this->reservation_start)
            ->whereDate('reservation_end', '<=', $this->reservation_end)
            ->where($attribute, $value)->first();

        return $reservation ? false : true;
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
