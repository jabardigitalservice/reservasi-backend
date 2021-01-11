<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use App\AsetResource;

class AsetResourceController extends Controller
{
    /**
     * Function to provided respond payload
     * @author by SedekahCode
     * @since Januari 2021
     * @param trueOrFalse bool
     * @param message string
     * @param data object
     * @return JSON
     */
    private function respondPayload($trueOrFalse, $message, $data) {
        $res = response([
            'success' => $trueOrFalse,
            'message' => $message,
            'data' => $data
        ],
        $trueOrFalse === true ? 200 : 500);

        return $res;
    }

    /**
     * Store a newly created resource in storage.
     * @author SedekahCode
     * @since Januari 2021
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation req body
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required|in:aktif, tidak aktif',
            'description' => 'required'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 200);
        }

        // start to create new record
        DB::beginTransaction();
        try {
            $aset = new AsetResource();
            $aset->name = $request->name;
            $aset->status = $request->status;
            $aset->description = $request->description;
            $aset->save();
            DB::commit();

            return $this->respondPayload(true, 'Aset has been succefully added.', $aset);

        } catch (\Throwable $th) {
            DB::rollback();

            return $this->respondPayload(false, 'Error while adding record.', null);
        }
    }

    /**
     * Updated a existing resource in storage.
     * @author SedekahCode
     * @since Januari 2021
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        // check exist or not
        $asetExists = AsetResource::find($id);
        if (!$asetExists) { 
            return $this->respondPayload(false, 'Aset with id '. $id .' is not exists.', null); 
        }

        // start to update a record
        DB::beginTransaction();
        try {
            $aset = $asetExists->fill($request->all())->save();
            DB::commit();

            return $this->respondPayload(true, 'Aset has been succefully updated.', $asetExists);

        } catch (\Throwable $th) {
            DB::rollback();

            return $this->respondPayload(false, 'Error while updating record.', null);
        }
    }

    /**
     * Get all data from storage
     * @author SedekahCode
     * @since Januari 2021
     */
    public function getAll() {
        return $this->respondPayload(true, 'Data aset was found', AsetResource::all());
    }

    /**
     * Get all data from storage
     * @author SedekahCode
     * @since Januari 2021
     */
    public function getById($id) {
        // check exist or not
        $asetExists = AsetResource::find($id);
        if (!$asetExists) { 
            return $this->respondPayload(false, 'Aset with id '. $id .' is not exists.', null); 
        }
        
        return $this->respondPayload(true, 'Data aset was found', $asetExists);
    }

    /**
     * Endpoint to search data based on nama
     * @author SedekahCode
     * 
     */
    public function searchByNameAndStatus(Request $request) {
        if ($request->has(['name', 'status'])) {
            $aset = AsetResource::where('name', 'LIKE', '%' . $request->name . '%' )->where('status', 'LIKE', '%' . $request->status . '%' )->get();
        } elseif ($request->has('name')) {
            $aset = AsetResource::where('name', 'LIKE', '%' . $request->name . '%' )->get();
        } else {
            $aset = AsetResource::where('status', 'LIKE', '%' . $request->status . '%' )->get();
        }
 
        if (count ( $aset ) > 0) {
            return $this->respondPayload(true, 'Data was found', $aset);
        } else {
            return $this->respondPayload(true, 'No data found. Try to search again', null);
        }
    }
}
