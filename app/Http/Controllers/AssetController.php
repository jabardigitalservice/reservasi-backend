<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAsetRequest;
use App\Http\Requests\EditAsetRequest;
use App\Http\Resources\AssetResource;
use App\Asset;

class AssetController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @author SedekahCode
     * @since Januari 2021
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAsetRequest $request, Asset $asset)
    {
        $result = $asset->create($request->all());

        return response()->json(['message' => 'Asset created!', 'data' => $result], 201);
    }

    /**
     * Updated a existing resource in storage.
     * @author SedekahCode
     * @since Januari 2021
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditAsetRequest $request, Asset $aset, $id)
    {
        // check exist or not
        $asetExists = $aset->find($id);
        if (!$asetExists) {
            return response()->json(['message' => 'Aset record with id '. $id .' is not exists.',], 404);
        }
        $asetExists->fill($request->all())->save();

        return response()->json(['message' => 'Asset updated!', 'data' => $asetExists], 200);
    }

    /**
     * Get all data from storage
     * @author SedekahCode
     * @since Januari 2021
     */
    public function getAllActive(Request $request, Asset $asset)
    {
        $result = $asset->where('status', 'active')->get();

        return response()->json(['result' => $result], 200);
    }

    /**
     * Get all data from storage
     * @author SedekahCode
     * @since Januari 2021
     */
    public function getById($id)
    {
        // check exist or not
        $asetExists = Asset::find($id);
        if (!$asetExists) {
            return response()->json(['message' => 'Asset record with id '. $id .' is not exists.'], 404);
        }
        return response()->json(['message' => 'Asset found', 'data' => $asetExists],200);
    }

    public function destroy($id)
    {
        // check exist or not
        $asetExists = Asset::find($id);
        if (!$asetExists) {
            return response()->json(['message' => 'Asset record with id '. $id .' is not exists.'], 404);
        }
        $asetExists->delete();

        return response()->json(['message' => 'Asset record deleted.'], 200);
    }

    public function getList(Request $request)
    {
        $records = Asset::query();
        $sortBy = $request->input('sortBy', 'created_at');
        $orderBy = $request->input('orderBy', 'desc');
        $perPage = $request->input('perPage', 10);
        $perPage = $this->getPaginationSize($perPage);

        // search list
        $records = $this->searchList($request, $records);

        // sort and order
        $records->orderBy($sortBy, $orderBy);

        return AssetResource::collection($records->paginate($perPage));
    }

    protected function getPaginationSize($perPage)
    {
        $perPageAllowed = [50, 100, 500];

        if (in_array($perPage, $perPageAllowed)) {
            return $perPage;
        }
        return 10;
    }

    protected function searchList(Request $request, $records)
    {
        if ($request->has('name')) {
            $records = $records->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->has('status')) {
            $records = $records->where('status', 'LIKE', '%' . $request->status . '%');
        }

        return $records;
    }
}
