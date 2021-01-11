<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAsetRequest;
use App\Http\Requests\EditAsetRequest;
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
     * @return JsonResponse
     */
    private function respondPayload($message, $data, $statusCode) {
        $res = response([
            'message' => $message,
            'data' => $data
        ],
        $statusCode);

        return $res;
    }

    /**
     * Store a newly created resource in storage.
     * @author SedekahCode
     * @since Januari 2021
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAsetRequest $request)
    {
        $result = AsetResource::create($request->all());

        return $this->respondPayload('Aset record created.', $result, 201);
    }

    /**
     * Updated a existing resource in storage.
     * @author SedekahCode
     * @since Januari 2021
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditAsetRequest $request, $id) {
        // check exist or not
        $asetExists = AsetResource::find($id);
        if (!$asetExists) { 
            return $this->respondPayload('Aset record with id '. $id .' is not exists.', null, 404); 
        }
        $asetExists->fill($request->all())->save();

        return $this->respondPayload('Aset record updated.', $asetExists, 200);
    }

    /**
     * Get all data from storage
     * @author SedekahCode
     * @since Januari 2021
     */
    public function getAll() {
        return $this->respondPayload('Data aset found', AsetResource::all(), 200);
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
            return $this->respondPayload('Aset record with id '. $id .' is not exists.', null, 404); 
        }
        
        return $this->respondPayload('Data aset found', $asetExists, 200);
    }

    public function destroy($id) {
        // check exist or not
        $asetExists = AsetResource::find($id);
        if (!$asetExists) { 
            return $this->respondPayload('Aset record with id '. $id .' is not exists.', null, 404); 
        }
        $asetExists->delete();

        return $this->respondPayload('Aset record deleted.', null, 200);
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
            return $this->respondPayload('Data aset found.', $aset, 200);
        } else {
            return $this->respondPayload('No data found. Try to search again.', null, 404);
        }
    }
}
