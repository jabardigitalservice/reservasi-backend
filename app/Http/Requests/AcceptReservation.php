<?php

namespace App\Http\Requests;

use App\Enums\ReservationStatusEnum;
use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class AcceptReservation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->role === UserRoleEnum::ADMIN();
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
                new EnumRule(ReservationStatusEnum::class),
            ],
            'note' => 'required',
        ];
    }
}
