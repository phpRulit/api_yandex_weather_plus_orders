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

Route::group([
    'prefix' => 'v1',
    'middleware' => 'api'
], function ($router) {
    Route::group([
        'namespace' => 'App\Http\Controllers'
    ], function () {
        Route::group([
            'prefix' => 'yandex',
            'namespace' => 'Yandex'
        ], function () {
            Route::get('set-weather', 'WeatherController@currentWeather');
        });
        Route::group([
            'prefix' => 'orders',
            'namespace' => 'Orders'
        ], function () {
            Route::get('get-list', 'HomeController@index');
            Route::get('get-order/{order_id}', 'EditController@order');
        });
    });

});
