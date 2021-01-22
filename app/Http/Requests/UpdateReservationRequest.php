<?php

namespace App\Http\Requests;

use App\Rules\UpdateAssetReservationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
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
            'title' => 'required',
            'asset_id' => [
                'required',
                'exists:assets,id,deleted_at,NULL',
                new UpdateAssetReservationRule($this->date, $this->start_time, $this->end_time, $this->reservation->id)
            ],
            'date' => 'required|date|date_format:Y-m-d',
            'start_time' => 'required|date|date_format:Y-m-d H:i',
            'end_time' => 'required|date|date_format:Y-m-d H:i|after:start_time',
        ];
    }
}
