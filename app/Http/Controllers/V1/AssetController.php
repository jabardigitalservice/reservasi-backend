<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditAssetRequest;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AssetController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:isAdmin');
    }

    /**
     * Function to list asset, also can search filter by name and status
     * @author SedekahCode
     * @since Januari 2021
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $records = Asset::query();
        $sortBy = $request->input('sortBy', 'name');
        $orderBy = $request->input('orderBy', 'desc');
        $perPage = $request->input('perPage', 10);
        $perPage = $this->getPaginationSize($perPage);

        // search list
        $records = $this->searchList($request, $records);

        // sort and order
        $records = $this->sortByStatus($sortBy, $orderBy, $records);

        return AssetResource::collection($records->paginate($perPage));
    }

    /**
     * Get all data from storage
     * @author SedekahCode
     * @since Januari 2021
     */
    public function show(Asset $asset)
    {
        return new AssetResource($asset);
    }

    /**
     * Store a newly created resource in storage.
     * @author SedekahCode
     * @since Januari 2021
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssetRequest $request)
    {
        $result = Asset::create($request->validated());

        return response()->json(null, Response::HTTP_CREATED);
    }

    /**
     * Updated a existing resource in storage.
     * @author SedekahCode
     * @since Januari 2021
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditAssetRequest $request, Asset $asset)
    {
        $asset->update($request->validated());

        response()->json(null, Response::HTTP_CREATED);
    }

    /**
     * Function to delete (soft delete) record
     * @author SedekahCode
     * @since Januari 2021
     * @param Asset $asset
     * @return void
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
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

    /**
     * Function to filter record by name or status
     * @author SedekahCode
     * @since Januari 2021
     * @param Request $request
     * @param Array $records
     * @return void
     */
    protected function searchList(Request $request, $records)
    {
        if ($request->has('name')) {
            $records = $records->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->has('status')) {
            $records = $records->where('status', $request->status);
        }

        return $records;
    }

    /**
     * Function to sort by status
     *
     * @param [String] $sortBy
     * @param [String] $orderBy
     * @param [Array] $records
     * @return void
     */
    protected function sortByStatus($sortBy, $orderBy, $records)
    {
        if ($sortBy == 'name') {
            $records->orderBy('status', 'asc');
        }

        return $records->orderBy($sortBy, $orderBy);
    }
}
