<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandCenterReservationRequest;
use App\Models\CommandCenterReservation;
use App\Enums\CommandCenterReservationStatusEnum;
use App\Http\Resources\CCReservationResource;
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
    public function index(Request $request)
    {
        $records = CommandCenterReservation::query();
        $perPage = $request->input('perPage', 10);
        $perPage = $this->getPaginationSize($perPage);

        return CCReservationResource::collection($records->paginate($perPage));
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
        return new CCReservationResource($commandCenterReservation);
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
        $commandCenterReservation->update($request->validated());

        return new CCReservationResource($commandCenterReservation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommandCenterReservation  $commandCenterReservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommandCenterReservation $commandCenterReservation)
    {
        $commandCenterReservation->delete();
    }

    /**
     * Function to pagination
     * @author SedekahCode
     * @since Januari 2021
     * @param Array $perPage
     * @return void
     */
    protected function getPaginationSize($perPage)
    {
        $perPageAllowed = [50, 100, 500];

        if (in_array($perPage, $perPageAllowed)) {
            return $perPage;
        }
        return 10;
    }
}
