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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('user', 'Settings\ProfileController@index');
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
