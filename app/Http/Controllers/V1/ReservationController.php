<?php

namespace App\Http\Controllers\V1;

use App\Enums\ReservationStatusEnum;
use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptReservation;
use App\Http\Requests\StoreReservation;
use App\Http\Resources\ReservationResource;
use App\Models\Asset;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $records = Reservation::query();
        $sortBy = $request->input('sortBy', 'date');
        $orderBy = $request->input('orderBy', 'desc');
        $perPage = $request->input('perPage', 10);
        $perPage = $this->getPaginationSize($perPage);

        //search
        if ($request->has('search')) {
            $records->where('title', 'like', '%' . $request->input('search') . '%');
        }

        //filter
        $records = $this->filterList($request, $records);

        //order
        $records->orderBy($sortBy, $orderBy);

        //check role employee
        if ($request->user()->role === UserRoleEnum::EMPLOYEE()) {
            $records->where('user_id_reservation', $request->user()->id);
        }

        return ReservationResource::collection($records->paginate($perPage));
    }

    public function store(StoreReservation $request)
    {
        $asset = Asset::find($request->asset_id);
        $user = $request->user();
        $reservation = Reservation::create([
            'user_id_reservation' => $user->id,
            'username' => $user->username,
            'title' => $request->title,
            'description' => $request->description,
            'asset_id' => $request->asset_id,
            'asset_name' => $asset->asset_name,
            'asset_description' => $asset->asset_description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return ReservationResource::collection($reservation);
    }

    public function accept(AcceptReservation $request, Reservation $reservation)
    {
        $reservation->approval_status = $request->approval_status;
        $reservation->note = $request->note;
        $reservation->approval_date = Carbon::now();
        $reservation->save();
        return new ReservationResource($reservation);
    }

    public function delete(Reservation $reservation)
    {
        abort_if($reservation->approval_status != ReservationStatusEnum::NOT_YET_APPROVED(), 500, 'error');
        $reservation->delete();
        return response()->json(['message' => 'DELETED']);
    }

    public function show(Reservation $reservation)
    {
        return new ReservationResource($reservation);
    }

    public function bookingList(Request $request)
    {
        $asset_id = $request->input('asset_id');
        $date = $request->input('date', date('Y-m-d'));

        $records = Reservation::where('date', $date);

        if ($asset_id) {
            $records->where('asset_id', $asset_id);
        }

        return ReservationResource::collection($records);
    }

    protected function getPaginationSize($perPage)
    {
        $perPageAllowed = [50, 100, 500];

        if (in_array($perPage, $perPageAllowed)) {
            return $perPage;
        }
        return 15;
    }

    protected function filterList(Request $request, $records)
    {
        if ($request->has('asset_id')) {
            $records->where('asset_id', $request->input('asset_id'));
        }
        if ($request->has('status')) {
            $records->where('approval_status', 'LIKE', '%' . $request->input('status') . '%');
        }
        if ($request->has('start_date')) {
            $records->whereDate('date', '>=', Carbon::parse($request->input('start_date')));
        }
        if ($request->has('end_date')) {
            $records->whereDate('date', '<=', Carbon::parse($request->input('end_date')));
        }
        return $records;
    }
}
