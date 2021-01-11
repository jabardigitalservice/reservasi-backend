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

//---ROUTE FOR ASET---//
Route::post('aset', 'AsetResourceController@store');
Route::put('aset/{idAset}', 'AsetResourceController@update');
Route::get('aset', 'AsetResourceController@getAll');
Route::get('aset/{idAset}', 'AsetResourceController@getById');
Route::delete('aset/{idAset}', 'AsetResourceController@destroy');
Route::get('search', 'AsetResourceController@searchByNameAndStatus');