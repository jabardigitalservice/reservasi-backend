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

//---ROUTE FOR ASSET---//
Route::prefix('asset')->group(function () {
    Route::get('/available-list', 'AssetController@getAllActive');
    Route::get('/', 'AssetController@getList');
    Route::post('/', 'AssetController@store');
    Route::get('/{asset}', 'AssetController@getById');
    Route::put('/{asset}', 'AssetController@update');
    Route::delete('/{asset}', 'AssetController@destroy');
});

Route::group(['prefix' => 'reservation', 'namespace' => 'V1'], function () {
    Route::get('/', 'ReservationController@index');
    Route::post('/', 'ReservationController@store')->middleware('can:isEmployee');
    Route::get('/{id}', 'ReservationController@show');
    Route::get('/booking-list', 'ReservationController@bookingList');
    Route::put('acceptance/{id}', 'ReservationController@acceptance')->middleware('can:isAdmin');
    Route::delete('/{id}', 'ReservationController@destroy')->middleware('can:isEmployee');
});
