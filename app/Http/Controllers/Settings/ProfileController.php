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
        $token = json_decode(Auth::token());

        $filteredRole = UserRoleEnum::employee_reservasi();

        foreach ($token->realm_access->roles as $role) {
            if (in_array($role, UserRoleEnum::toArray())) {
                $filteredRole = $role;
            }
        }

        $data = [
            'id' => $token->sub,
            'name' => $token->name,
            'username' => $token->preferred_username,
            'email' => $token->email,
            'role' => $filteredRole,
        ];

        return response()->json(['message' => 'success', 'status' => 200, 'data' => $data]);
    }
}
