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


Route::get('member/total', [
    'uses' => 'Api\MemberController@total',
    'as' => 'api.member.total'
]);

Route::get('deposit/total', [
    'uses' => 'Api\DepositController@total',
    'as' => 'api.deposit.total'
]);

Route::get('profit/total', [
    'uses' => 'Api\ProfitController@total',
    'as' => 'api.profit.total'
]);