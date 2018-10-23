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

//! 基本接口
Route::group(['prefix' => 'ftenlog'], function () {
    Route::post('', '\App\Ftenlog\Ftl\FtlController@ftenlog');
    //! 用户接口
    Route::post('/user', '\App\Ftenlog\Ftl\User\FtlUserController@ftenlog');
    //! 交易接口
    Route::post('/trade', '\App\Ftenlog\Ftl\Trade\FtlTradeController@ftenlog');
});
