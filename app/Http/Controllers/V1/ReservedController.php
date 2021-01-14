<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptReservation;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enums\ReservationStatusEnum;

class ReservedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $asset_id = $request->input('asset_id');
        $date = $request->input('date', date('Y-m-d'));

        $records = Reservation::where('date', $date)->where('approval_status', ReservationStatusEnum::already_approved());

        if ($asset_id) {
            $records->where('asset_id', $asset_id);
        }

        return ReservationResource::collection($records->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AcceptReservation $request, Reservation $reservation)
    {
        $reservation->approval_status = $request->approval_status;
        $reservation->note = $request->note;
        $reservation->approval_date = Carbon::now();
        $reservation->user_id_updated = User::getUser()->id;
        $reservation->save();
        return new ReservationResource($reservation);
    }
}
