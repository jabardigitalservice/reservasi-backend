<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandCenterReservationRequest;
use App\Models\CommandCenterReservation;
use App\Enums\CommandCenterReservationStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class CommandCenterReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommandCenterReservationRequest $request)
    {
        try {
            DB::beginTransaction();

            $reservation = CommandCenterReservation::create($request->validated() + [
                'user_id_reservation' => $request->user()->uuid,
                'approval_status' => CommandCenterReservationStatusEnum::already_approved(),
            ]);

            DB::commit();

            return response()->json(['message' => 'OK', 'data' => $reservation], Response::HTTP_CREATED);
        } catch (\Exception $th) {
            DB::rollback();
            return response()->json(['message' => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CommandCenterReservation  $commandCenterReservation
     * @return \Illuminate\Http\Response
     */
    public function show(CommandCenterReservation $commandCenterReservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommandCenterReservation  $commandCenterReservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommandCenterReservation $commandCenterReservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommandCenterReservation  $commandCenterReservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommandCenterReservation $commandCenterReservation)
    {
        //
    }
}
