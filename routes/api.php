<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\AsetResourceController;

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
Route::post('asset', 'AssetController@store');
Route::put('asset/{asset}', 'AssetController@update');
Route::get('asset', 'AssetController@getList');
Route::get('asset-available-list', 'AssetController@getAllActive');
Route::get('asset/{asset}', 'AssetController@getById');
Route::delete('asset/{asset}', 'AssetController@destroy');
