<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\User;
use App\Enums\UserRoleEnum;
use App\Enums\ReservationStatusEnum;

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
        $reservation = Reservation::query();

        //check role employee reservasi
        if (User::getUser()->role == UserRoleEnum::employee_reservasi()) {
            $reservation->where('user_id_reservation', User::getUser()->id);
        }

        $statistic = [
            'all' => $reservation->count(),
            'not_yet_approved' => $reservation->where('approval_status', ReservationStatusEnum::not_yet_approved())->count(),
            'already_approved' => $reservation->where('approval_status', ReservationStatusEnum::already_approved())->count(),
            'rejected' => $reservation->where('approval_status', ReservationStatusEnum::rejected())->count(),
        ];

        return $statistic;
    }
}
