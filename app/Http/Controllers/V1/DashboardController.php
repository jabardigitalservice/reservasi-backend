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
        $all = Reservation::query();
        $notYetApproved = Reservation::where('approval_status', ReservationStatusEnum::not_yet_approved());
        $alreadyApproved = Reservation::where('approval_status', ReservationStatusEnum::already_approved());
        $rejected = Reservation::where('approval_status', ReservationStatusEnum::rejected());
        
        //check role employee reservasi
        if (User::getUser()->role == UserRoleEnum::employee_reservasi()) {
            $all->where('user_id_reservation', User::getUser()->id);
            $notYetApproved->where('user_id_reservation', User::getUser()->id);
            $alreadyApproved->where('user_id_reservation', User::getUser()->id);
            $rejected->where('user_id_reservation', User::getUser()->id);
        }
        
        $statistic = [
            'all' => $all->count(),
            'not_yet_approved' => $notYetApproved->count(),
            'already_approved' => $alreadyApproved->count(),
            'rejected' => $rejected->count(),
        ];

        return $statistic;
    }
}
