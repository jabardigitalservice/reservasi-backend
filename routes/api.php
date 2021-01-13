<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/', 'HomeController');

// public endpoints
Route::get('/ping', function() {
    $response = Response::make(gethostname(), 200);
    $response->header('Content-Type', 'text/plain');
    return $response;
});

// protected endpoints

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/protected-ping', function () {
        $token = json_decode(Auth::token());
        $data = [
            'id' => $token->sub,
            'name' => $token->name,
            'username' => $token->preferred_username,
            'email' => $token->email,
            'token' => $token,
            'has_role' => Auth::hasRole('reservasi-asset-web', 'create-reservation')
        ];
        return $data;
    });

    Route::apiResource('asset', 'AssetController');
    Route::get('asset/list', 'ListController@index');
    Route::group(['namespace' => 'V1'], function () {
        Route::apiResource('reservation', 'ReservationController')->except('update');
        Route::apiResource('reserved', 'ReservedController')
            ->only(['index', 'update'])
            ->parameters([
                'reserved' => 'reservation',
            ]);
    });
});
