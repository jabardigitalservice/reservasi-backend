<?php

namespace App\Http\Controllers\V1;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationApprovalStatus;

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
    public function update(AcceptReservationRequest $request, Reservation $reservation)
    {
        $reservation->approval_status = $request->approval_status;
        $reservation->note = $request->note;
        $reservation->approval_date = Carbon::now();
        $reservation->user_id_updated = Auth::user()->id;
        $reservation->save();
        
        Mail::to($reservation->email)->send(new ReservationApprovalStatus($reservation, $request->approval_status));
        return new ReservationResource($reservation);
    }
}
