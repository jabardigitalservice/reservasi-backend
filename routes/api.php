<?php

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
Route::group(['namespace' => 'V1'], function () {
    Route::post('/zoom-create-room-test', 'ReservationController@createZoom');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('user', 'Settings\ProfileController@index')->name('user.get');
    Route::group(['namespace' => 'V1'], function () {
        Route::get('asset/list', 'ActiveListAssetController@index')->name('asset.list');
        Route::apiResource('asset', 'AssetController');
        Route::apiResource('reservation', 'ReservationController');
        Route::apiResource('reserved', 'ReservedController')
            ->only(['index', 'update'])
            ->parameters([
                'reserved' => 'reservation',
            ]);
        Route::get('dashboard/reservation-statistic', 'DashboardController@reservationStatistic')->name('reservation.dashboard');
    });
});
