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
        $sortBy = $request->input('sortBy', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $perPage = $request->input('per_page', 15);
        $perPage = $this->getPaginationSize($perPage);

        //search
        if ($request->has('search')) {
            $records->where('title', 'like', '%' . $request->input('search') . '%');
        }

        //filter
        $records = $this->filterList($request, $records);

        //order
        $records->orderBy($sortBy, $sortOrder);

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
            'reservation_title' => $request->reservation_title,
            'reservation_description' => $request->reservation_description,
            'asset_id' => $request->asset_id,
            'asset_name' => $asset->asset_name,
            'asset_description' => $asset->asset_description,
            'reservation_start' => $request->reservation_start,
            'reservation_end' => $request->reservation_end,
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
        if ($request->has('created_at')) {
            $records->whereDate('created_at', date('Y-m-d'));
        }
        if ($request->has('approval_status')) {
            $records->where('approval_status', $request->input('approval_status'));
        }
        if ($request->has('reservation_start')) {
            $records->where('created_at', '>=', $request->input('reservation_start'));
        }
        if ($request->has('reservation_end')) {
            $records->where('created_at', '>=', $request->input('reservation_end'));
        }
        return $records;
    }
}
