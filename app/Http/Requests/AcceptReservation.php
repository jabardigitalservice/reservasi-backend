<?php

namespace App\Http\Requests;

use App\Enums\ReservationStatusEnum;
use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use App\User;

class AcceptReservation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return User::getUser()->role == UserRoleEnum::admin_reservasi();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'approval_status' => [
                'required',
                'enum:' . ReservationStatusEnum::class,
            ],
            'note' => 'required',
        ];
    }
}
