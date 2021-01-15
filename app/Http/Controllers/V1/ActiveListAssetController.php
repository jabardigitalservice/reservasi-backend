<?php

namespace App\Http\Controllers\V1;

use App\Models\Asset;
use App\Http\Controllers\Controller;
use App\Http\Resources\AssetResource;
use Illuminate\Http\Request;

class ActiveListAssetController extends Controller
{
    /**
     * Get all data from storage
     * @author SedekahCode
     * @since Januari 2021
     */
    public function index()
    {
        $result = Asset::where('status', 'active')->get();

        return new AssetResource($result);
    }
}
