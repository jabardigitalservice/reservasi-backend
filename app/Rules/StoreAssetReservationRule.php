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
        return $this->checkAssetTime($attribute, $value, 'start_time') &&
        $this->checkAssetTime($attribute, $value, 'end_time');
    }

    /**
     * checkAssetTime
     *
     * @param  mixed $field
     * @param  mixed $value
     * @param  mixed $status_time
     * @return void
     */
    public function checkAssetTime($field, $value, $status_time)
    {
        $start_time = Carbon::parse($this->start_time);
        $end_time = Carbon::parse($this->end_time);
        $record = Reservation::whereBetween('start_time', [$start_time, $end_time])
            ->orWhereBetween('end_time', [$start_time, $end_time]);
        switch ($status_time) {
            case 'start_time':
                $record->orWhereTime('start_time', '>', $start_time->addSecond(1))
                    ->WhereTime('end_time', '<', $end_time);
                break;

            case 'end_time':
                $record->orWhereTime('start_time', '>', $start_time)
                    ->WhereTime('end_time', '<', $end_time)->subSeconds(1);
                break;
        }
        return $record->where('date', Carbon::parse($this->date))
            ->where($field, $value)
            ->doesntExist();
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
