<?php

namespace App\Http\Controllers\V1;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptReservationRequest;
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
        $date = $request->input('date');

        $records = Reservation::whereDate('date', Carbon::parse($date))
            ->whereIn('approval_status', [
                ReservationStatusEnum::already_approved(),
                ReservationStatusEnum::not_yet_approved(),
            ]);

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
    public function update(AcceptReservationRequest $request, Reservation $reservation)
    {
        $request->request->add([
            'approval_date' => Carbon::now(),
            'user_id_updated' => $request->user()->uuid
        ]);
        $reservation->fill($request->all())->save();
        return new ReservationResource($reservation);
    }
}
