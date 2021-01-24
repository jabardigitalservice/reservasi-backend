<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use App\Rules\AssetReservationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Reservation::VALIDATION_RULES;
        $rules['asset_id'][] = new AssetReservationRule(
            $this->date,
            $this->start_time,
            $this->end_time,
        );
        return $rules;
    }
}
