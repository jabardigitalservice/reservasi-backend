<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptReservation;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservedController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('can:isAdmin')->only('update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $asset_id = $request->input('asset_id');
        $date = $request->input('date', date('Y-m-d'));

        $records = Reservation::where('date', $date);

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
        $reservation->user_id_updated = $request->user()->id;
        $reservation->save();
        return new ReservationResource($reservation);
    }
}
