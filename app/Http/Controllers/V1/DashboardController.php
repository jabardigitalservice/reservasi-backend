<?php

namespace App\Http\Controllers\V1;

use App\Enums\UserRoleEnum;
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
        $all = Reservation::query();
        $not_yet_approved = Reservation::notYetApproved();
        $already_approved = Reservation::alreadyApproved();
        $rejected = Reservation::rejected();

        if ($request->user()->role == UserRoleEnum::employee_reservasi()) {
            $all->byUser($request->user());
            $not_yet_approved->byUser($request->user());
            $already_approved->byUser($request->user());
            $rejected->byUser($request->user());
        }
        return [
            'all' => $all->count('username'),
            'not_yet_approved' => $not_yet_approved->count('username'),
            'already_approved' => $already_approved->count('username'),
            'rejected' => $rejected->count('username')
        ];
    }
}
