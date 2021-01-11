<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            $aset->createdAt = \Carbon\Carbon::now();
            // temporary id admin are harcoded, because next step it will use id from SSO
            $aset->createdBy = '123';
            $aset->save();
            DB::commit();

            return $this->respondPayload(true, 'Berhasil', $aset);

        } catch (\Throwable $th) {
            DB::rollback();

            return $this->respondPayload(false, 'Gagal', null);
        }
    }
}
