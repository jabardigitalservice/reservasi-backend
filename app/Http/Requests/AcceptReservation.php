<?php

namespace App\Http\Requests;

use App\Enums\ReservationStatus;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class AcceptReservation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->role == UserRole::ADMIN()->getValue();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $already_approved = ReservationStatus::ALREADY_APPROVED();
        $rejected = ReservationStatus::REJECTED();
        return [
            'approval_status' => "required|in:$already_approved,$rejected",
            'note' => 'required'
        ];
    }
}
