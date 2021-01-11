<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use App\User;
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
        return $this->user()->role == User::ADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $alreadyApproved = strtolower(Reservation::ALREADY_APPROVED);
        $rejected = strtolower(Reservation::REJECTED);
        return [
            'approval_status' => "required|in:$alreadyApproved,$rejected",
            'note' => 'required'
        ];
    }
}
