<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommandCenterReservationRequest extends FormRequest
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
            'name' => 'string|required|max:100',
            'nik' => 'string|required|max:16',
            'organization_name' => 'string|nullable|max:50',
            'address' => 'string|nullable|max:100',
            'phone_number' => 'string|required|max:14',
            'email' => 'required|unique:command_center_reservations',
            'purpose' => 'string|required|max:50',
            'visitors' => 'integer|required|max:20',
            'reservation_date' => 'required|date',
            'shift' => 'required|in:1,2'
        ];
    }
}
