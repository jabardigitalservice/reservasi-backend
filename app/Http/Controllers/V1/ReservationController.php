<?php

namespace App\Http\Controllers\V1;

use App\Enums\ReservationStatusEnum;
use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Asset;
use App\Models\Reservation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */
    public function index(Request $request)
    {
        $records = Reservation::query();
        $sortBy = $request->input('sortBy', 'created_at');
        $orderBy = $request->input('orderBy', 'desc');
        $perPage = $request->input('perPage', 10);
        $perPage = $this->getPaginationSize($perPage);

        //search
        if ($request->has('search')) {
            $records->where('title', 'LIKE', '%' . $request->input('search') . '%');
        }

        //filter
        $records = $this->filterList($request, $records);

        //order
        $records = $this->sortBy($sortBy, $orderBy, $records);

        //check role employee reservasi
        if (User::getUser()->role === UserRoleEnum::employee_reservasi()) {
            $records->where('user_id_reservation', $request->user()->id);
        }

        return ReservationResource::collection($records->paginate($perPage));
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StoreReservationRequest $request)
    {
        $asset = Asset::find($request->asset_id);
        $user = User::getUser();
        $reservation = Reservation::create([
            'user_id_reservation' => $user->id,
            'user_fullname' => $user->name,
            'username' => $user->username,
            'title' => $request->title,
            'description' => $request->description,
            'asset_id' => $request->asset_id,
            'asset_name' => $asset->name,
            'asset_description' => $asset->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return new ReservationResource($reservation);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        abort_if($reservation->approval_status != ReservationStatusEnum::NOT_YET_APPROVED(), 500, 'error');
        $asset = Asset::find($request->asset_id);
        $user = User::getUser();
        $reservation = $reservation->update([
            'title' => $request->title,
            'description' => $request->description,
            'asset_id' => $request->asset_id,
            'asset_name' => $asset->name,
            'asset_description' => $asset->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'user_id_updated' => $user->id,
        ]);
        
        return response()->json(['message' => 'UPDATED']);
    }

    /**
     * destroy
     *
     * @param  mixed $reservation
     * @return void
     */
    public function destroy(Reservation $reservation)
    {
        abort_if($reservation->approval_status != ReservationStatusEnum::NOT_YET_APPROVED(), 500, 'error');
        $reservation->delete();
        return response()->json(['message' => 'DELETED']);
    }

    /**
     * show
     *
     * @param  mixed $reservation
     * @return void
     */
    public function show(Reservation $reservation)
    {
        return new ReservationResource($reservation);
    }

    /**
     * getPaginationSize
     *
     * @param  mixed $perPage
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

    /**
     * filterList
     *
     * @param  mixed $request
     * @param  mixed $records
     * @return void
     */
    protected function filterList(Request $request, $records)
    {
        if ($request->has('asset_id')) {
            $records->where('asset_id', $request->input('asset_id'));
        }
        if ($request->has('approval_status')) {
            $records->where('approval_status', 'LIKE', '%' . $request->input('approval_status') . '%');
        }
        if ($request->has('start_date')) {
            $records->where('date', '>=', Carbon::parse($request->input('start_date')));
        }
        if ($request->has('end_date')) {
            $records->where('date', '<=', Carbon::parse($request->input('end_date')));
        }
        return $records;
    }

    protected function sortBy($sortBy, $orderBy, $records)
    {
        if ($sortBy === 'reservation_time') {
            return $records->orderBy('date', $orderBy)
                ->orderBy('start_time', $orderBy)
                ->orderBy('end_time', $orderBy);
        }
        return $records->orderBy($sortBy, $orderBy);
    }
}
