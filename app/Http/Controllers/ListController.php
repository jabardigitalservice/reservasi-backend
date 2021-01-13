<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Http\Resources\AssetResource;
use Illuminate\Http\Request;

class ListController extends Controller
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
