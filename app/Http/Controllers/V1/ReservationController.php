<?php

namespace App\Http\Controllers\V1;

use App\Enums\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Asset;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:isEmployee')->only(['store', 'update']);
    }
    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */
    public function index(Request $request)
    {
        $records = Reservation::checkRoleEmployee();
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
        $request->request->add([
            'user_id_reservation' => $request->user()->uuid,
            'user_fullname' => $request->user()->name,
            'username' => $request->user()->username,
            'asset_name' => $asset->name,
            'asset_description' => $asset->description,
        ]);
        $reservation = Reservation::create($request->all());

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
        abort_if($reservation->notYetApproved(), 500, __('validation.asset_modified'));
        $asset = Asset::find($request->asset_id);
        $request->request->add([
            'asset_name' => $asset->name,
            'asset_description' => $asset->description,
            'user_id_updated' => $request->user()->uuid
        ]);
        $reservation->fill($request->all())->save();
        return new ReservationResource($reservation);
    }

    /**
     * destroy
     *
     * @param  mixed $reservation
     * @return void
     */
    public function destroy(Reservation $reservation)
    {
        abort_if($reservation->notYetApproved(), 500, __('validation.asset_modified'));
        $reservation->delete();
        return response()->json(['message' => 'Reservation record deleted.']);
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
            $records->whereDate('date', '>=', Carbon::parse($request->input('start_date')));
        }
        if ($request->has('end_date')) {
            $records->whereDate('date', '<=', Carbon::parse($request->input('end_date')));
        }
        return $records;
    }

    /**
     * sortBy
     *
     * @param  mixed $sortBy
     * @param  mixed $orderBy
     * @param  mixed $records
     * @return void
     */
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
