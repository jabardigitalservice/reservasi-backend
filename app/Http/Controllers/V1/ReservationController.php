<?php

namespace App\Http\Controllers\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptReservation;
use App\Http\Requests\StoreReservation;
use App\Http\Resources\ReservationResource;
use App\Models\Asset;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $records = Reservation::query();

        $search = $request->get('search', false);
        $sortBy = $request->get('sortBy', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $perPage = $request->input('per_page', 15);

        $perPage = $this->getPaginationSize($perPage);

        //search
        if ($search) {
            $records->where('title', 'like', '%' . $search . '%');
        }

        //filter
        if ($request->has('date')) {
            $records->whereDate('created_at', date('Y-m-d'));
        } elseif ($request->has('approval_status')) {
            $records->where('approval_status', $request->get('approval_status'));
        } elseif ($request->has('reservation_start')) {
            $records->where('created_at', '>=', $request->get('reservation_start'));
        } elseif ($request->has('reservation_end')) {
            $records->where('created_at', '>=', $request->get('reservation_end'));
        }

        //order
        if ($sortBy == 'date') {
            $sortBy = 'created_at';
        }

        $records->orderBy($sortBy, $sortOrder);

        //check role employee
        if ($request->user()->role === UserRole::EMPLOYEE()) {
            $records->where('user_id_reservation', $request->user()->id);
        }

        if (strtoupper($perPage) === 'ALL') {
            return ReservationResource::collection($records->get());
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
        $reservation->save();
        return new ReservationResource($reservation);
    }

    public function delete(Reservation $reservation)
    {
        abort_if($reservation->approval_status != 'not_yet_approved', 500, 'error');
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
}
