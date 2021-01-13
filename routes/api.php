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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('login', 'AuthenticationController')->name('login');

// public endpoints
Route::get('/ping', function() {
    $response = Response::make(gethostname(), 200);
    $response->header('Content-Type', 'text/plain');
    return $response;
});

// protected endpoints
Route::group(['middleware' => 'auth:api'], function () {
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
    // more endpoints ...
});

Route::group(['prefix' => 'reservation', 'namespace' => 'V1'], function () {
    Route::get('/', 'ReservationController@index');
    Route::post('/', 'ReservationController@store')->middleware('can:isEmployee');
    Route::get('/{id}', 'ReservationController@show');
    Route::get('/booking-list', 'ReservationController@bookingList');
    Route::put('acceptance/{id}', 'ReservationController@acceptance')->middleware('can:isAdmin');
    Route::delete('/{id}', 'ReservationController@destroy')->middleware('can:isEmployee');
});
