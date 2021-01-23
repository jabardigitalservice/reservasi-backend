<?php

namespace App\Http\Controllers\V1;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
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
        $result['all'] = Reservation::checkRoleEmployee()
            ->count('username');
        $result['not_yet_approved'] = Reservation::checkRoleEmployee()
            ->approvalStatus(ReservationStatusEnum::not_yet_approved())
            ->count('username');
        $result['already_approved'] = Reservation::checkRoleEmployee()
            ->approvalStatus(ReservationStatusEnum::already_approved())
            ->count('username');
        $result['rejected'] = Reservation::checkRoleEmployee()
            ->approvalStatus(ReservationStatusEnum::rejected())
            ->count('username');
        return $result;
    }
}
