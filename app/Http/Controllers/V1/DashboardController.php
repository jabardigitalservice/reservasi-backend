<?php

namespace App\Http\Controllers\V1;

use App\Enums\ReservationStatusEnum;
use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Reservation Statistic
     *
     * @param  mixed $request
     * @return void
     */
    public function reservationStatistic(Request $request)
    {
        return [
            'all' => $this->totalReservationByStatus(),
            'not_yet_approved' => $this->totalReservationByStatus(ReservationStatusEnum::not_yet_approved()),
            'already_approved' => $this->totalReservationByStatus(ReservationStatusEnum::already_approved()),
            'rejected' => $this->totalReservationByStatus(ReservationStatusEnum::rejected()),
        ];
    }

    /**
     * totalReservationByStatus
     *
     * @param  mixed $status
     * @return void
     */
    public function totalReservationByStatus($status = false)
    {
        $record = Reservation::query();

        if (User::getUser()->role == UserRoleEnum::employee_reservasi()) {
            $record->where('user_id_reservation', User::getUser()->id);
        }

        if ($status) {
            $record->where('approval_status', $status);
        }

        return $record->count('title');
    }
}
