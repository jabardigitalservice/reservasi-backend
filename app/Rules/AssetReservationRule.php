<?php

namespace App\Rules;

use App\Models\Reservation;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class AssetReservationRule implements Rule
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
    public function __construct($date, $start_time, $end_time, $id = null)
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
        $reservations = Reservation::where($attribute, $value)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereTime('start_time', '<=', $this->start_time)
                        ->whereTime('end_time', '>', $this->start_time);
                })
                ->orWhere(function ($query) {
                    $query->whereTime('start_time', '<', $this->end_time)
                        ->whereTime('end_time', '>', $this->end_time);
                });
            })
            ->alreadyApproved()
            ->where(function ($query) {
                if ($this->id) {
                    $query->where('id', '!=', $this->id);
                }
            });
        return $reservations->doesntExist();
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
