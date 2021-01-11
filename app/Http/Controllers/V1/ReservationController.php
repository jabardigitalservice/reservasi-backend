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
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $records = Reservation::query();

        $search = $request->get('search', false);
        $sortBy = $request->get('sortBy', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $perPage = $request->input('per_page', 15);

        if ($search) {
            $records->where('title', 'like', '%' . $search . '%');
        }

        if ($request->has('date')) {
            $records->whereDate('created_at', date('Y-m-d'));
        } elseif ($request->has('approval_status')) {
            $records->where('approval_status', $request->get('approval_status'));
        } elseif ($request->has('reservation_start')) {
            $records->where('created_at', '>=', $request->get('reservation_start'));
        } elseif ($request->has('reservation_end')) {
            $records->where('created_at', '>=', $request->get('reservation_end'));
        } elseif ($sortBy == 'date') {
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
        DB::beginTransaction();
        try {
            $asset = Asset::find($request->asset_id);
            $user = $request->user();
            Reservation::create([
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
            DB::commit();
            return response()->json(['code' => 200, 'message' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['code' => 500, 'message' => 'error'], 500);
        }
    }

    public function accept(AcceptReservation $request, $id)
    {
        DB::beginTransaction();
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->approval_status = $request->approval_status;
            $reservation->note = $request->note;
            $reservation->save();
            DB::commit();
            return response()->json(['code' => 200, 'message' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['code' => 500, 'message' => 'error'], 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $reservation = Reservation::findOrFail($id);
            abort_if($reservation->approval_status != 'not_yet_approved', 500, 'error');
            $reservation->delete();
            DB::commit();
            return response()->json(['code' => 200, 'message' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['code' => 500, 'message' => 'error'], 500);
        }
    }

    public function show($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            return response()->json(['code' => 200, 'result' => $reservation], 200);
        } catch (\Throwable $th) {
            return response()->json(['code' => 500, 'message' => 'error'], 500);
        }
    }
}
