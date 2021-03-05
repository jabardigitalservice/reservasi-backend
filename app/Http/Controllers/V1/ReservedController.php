<?php

namespace App\Http\Controllers\V1;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReservedController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:isAdmin')->only('update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id,deleted_at,NULL',
            'date' => 'required|date|date_format:Y-m-d'
        ]);
        $asset_id = $request->input('asset_id');
        $date = $request->input('date');

        $records = Reservation::whereDate('date', $date)
            ->whereIn('approval_status', [
                ReservationStatusEnum::already_approved(),
                ReservationStatusEnum::not_yet_approved(),
            ])
            ->where('asset_id', $asset_id)
            ->get();

        return ReservationResource::collection($records);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AcceptReservationRequest $request, Reservation $reservation)
    {
        $reservation->update($request->validated() + [
            'approval_date' => Carbon::now(),
        ]);
        return response()->json(null, Response::HTTP_CREATED);
    }
}
