<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('api/v1/exchangeInfo', 'ApiController@showExchangeInfo');
Route::get('api/v1/exchangeInfo/refresh', 'ApiController@refreshExchangeInfo');

