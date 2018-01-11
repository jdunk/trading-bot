<?php

use Illuminate\Http\Request;

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

Route::get('v1/exchangeInfo', 'ApiController@showExchangeInfo');

Route::get('v1/exchangeInfo/refresh', 'ApiController@refreshExchangeInfo');
Route::get('v1/candlesticks/update', 'ApiController@updateCandlesticks');
