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

Route::group(['prefix' => 'reservation'], function () {
    Route::get('/', 'ReservationController@index');
    Route::post('store', 'ReservationController@store');
    Route::put('accept/{reservation}', 'ReservationController@accept');
    Route::delete('delete/{reservation}', 'ReservationController@delete');
    Route::get('show/{reservation}', 'ReservationController@show');
});
