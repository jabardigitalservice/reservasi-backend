<?php

namespace App\Http\Controllers\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptReservation;
use App\Http\Requests\StoreReservation;
use App\Models\Asset;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $models = Reservation::query();

        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order = $request->get('order', 'date');

        if ($search != '') {
            $models = $models->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $key => $val) {
                if ($val !== false && ($val == '' || is_array($val) && count($val) == 0)) {
                    continue;
                }
                switch ($key) {
                    case 'today':
                        $models->whereDate('created_at', date('Y-m-d'));
                        break;
                    case 'approval_status':
                        $models->where('approval_status', $val);
                        break;
                    case 'reservation_start':
                        $models->whereDate('created_at', '>=', Carbon::parse($val)->format('Y-m-d'));
                        break;
                    case 'reservation_end':
                        $models->whereDate('created_at', '<=', Carbon::parse($val)->format('Y-m-d'));
                        break;
                }
            }
        }

        if ($order) {
            $order_direction = $request->get('order_direction', 'desc');
            switch ($order) {
                case 'date':
                    $models = $models->orderBy('created_at', $order_direction);
                    break;
                case 'title':
                case 'reservation_start':
                case 'approval_status':
                    $models = $models->orderBy($order, $order_direction);
                    break;
                default:
                    break;
            }
        }

        //check role employee
        if ($request->user()->role === UserRole::EMPLOYEE()->getValue()) {
            $models = $models->where('user_id_reservation', $request->user()->id);
        }

        $count = $models->count();
        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 20);

        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();

        $result = [
            'data' => $models,
            'count' => $count,
        ];

        return response()->json($result);
    }

    public function store(StoreReservation $request)
    {
        DB::beginTransaction();
        try {
            $asset = Asset::findOrFail($request->asset_id);
            $user = $request->user();
            $reservation = new Reservation();
            $reservation->user_id_reservation = $user->id;
            $reservation->username = $user->username;
            $reservation->reservation_title = $request->reservation_title;
            $reservation->reservation_description = $request->reservation_description;
            $reservation->asset_id = $request->asset_id;
            $reservation->asset_name = $asset->asset_name;
            $reservation->asset_description = $asset->asset_description;
            $reservation->reservation_start = Carbon::parse($request->reservation_start)->format('Y-m-d H:i:s');
            $reservation->reservation_end = Carbon::parse($request->reservation_end)->format('Y-m-d H:i:s');
            $reservation->save();
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
