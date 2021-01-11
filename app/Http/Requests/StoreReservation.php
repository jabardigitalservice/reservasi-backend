<?php

namespace App\Http\Requests;

use App\Rules\AssetReservation;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservation extends FormRequest
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
        return [
            'reservation_title' => 'required',
            'asset_id' => [
                'required',
                'exists:asset,id',
                new AssetReservation($this->reservation_start, $this->reservation_end)
            ],
            'reservation_start' => 'required|date|date_format:Y-m-d H:i:s',
            'reservation_end' => 'required|date|date_format:Y-m-d H:i:s|after:reservation_start',
        ];
    }


}
