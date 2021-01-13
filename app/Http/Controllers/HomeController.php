<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
       /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $token = json_decode(Auth::token());
        $data = [
            'id' => $token->sub,
            'name' => $token->name,
            'username' => $token->preferred_username,
            'email' => $token->email,
            'realm_access' => $token->realm_access
        ];
        return response()->json(['message' => 'success', 'status' => 200, 'data' => $data]);
    }
}
