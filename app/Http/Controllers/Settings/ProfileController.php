<?php

namespace App\Http\Controllers\Settings;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */

    public function index(Request $request)
    {
        return response()->json(['message' => 'success', 'status' => 200, 'data' => $request->user()]);
    }
}
