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

//---ROUTE FOR ASSET---//
Route::prefix('v1')->group(function () {
    Route::apiResource('asset', 'AssetController');
    Route::get('asset/list', 'ListController@index');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', 'Settings\ProfileController@index');
    Route::group(['prefix' => 'reservation', 'namespace' => 'V1'], function () {
        Route::get('/', 'ReservationController@index');
        Route::post('/', 'ReservationController@store')->middleware('can:isEmployee');
        Route::get('/{id}', 'ReservationController@show');
        Route::get('/booking-list', 'ReservationController@bookingList');
        Route::put('acceptance/{id}', 'ReservationController@acceptance')->middleware('can:isAdmin');
        Route::delete('/{id}', 'ReservationController@destroy')->middleware('can:isEmployee');
    });
});
