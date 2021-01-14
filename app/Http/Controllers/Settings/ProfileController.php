<?php

namespace App\Http\Controllers\Settings;

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
        $token = json_decode(Auth::token());
        $data = [
            'id' => $token->sub,
            'name' => $token->name,
            'username' => $token->preferred_username,
            'email' => $token->email,
            'realm_access' => $token->realm_access,
        ];
        return response()->json(['message' => 'success', 'status' => 200, 'data' => $data]);
    }
}
