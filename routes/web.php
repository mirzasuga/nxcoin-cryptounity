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
    //return view('login');
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {

    Route::get('package',[

        'uses' => 'PackageController@index',
        'as' => 'package.index'

    ]);
    Route::post('package/subscribe', [
        'uses' => 'PackageController@subscribe',
        'as' => 'package.subscribe'
    ]);

    Route::get('dashboard',[
        'uses' => 'DashboardController@index',
        'as' => 'dashboard'
    ]);

    Route::get('wallet', [
        'uses' => 'WalletController@index',
        'as' => 'wallet'
    ]);
    Route::post('wallet', [
        'uses' => 'WalletController@create',
        'as' => 'wallet.create'
    ]);
    Route::post('wallet/update', [
        'uses' => 'WalletController@update',
        'as' => 'wallet.update'
    ]);

    Route::get('stacking', [
        'uses' => 'StackingController@index',
        'as' => 'stacking'
    ]);
    Route::post('stacking', [
        'uses' => 'StackingController@create',
        'as' => 'stacking.create'
    ]);
    Route::get('stacking/terminate/{stackId}', [
        'uses' => 'StackingController@terminate',
        'as' => 'stacking.terminate'
    ]);

    Route::get('transactions',[
        'uses' => 'TransactionController@index',
        'as' => 'transaction'
    ]);

    Route::get('bonus/taking',[
        'uses' => 'BonusController@take',
        'as' => 'bonus.taking'
    ]);

    Route::get('profile',[
        'uses' => 'ProfileController@index',
        'as' => 'profile'
    ]);

    Route::get('affiliate',[
        'uses' => 'AffiliateController@index',
        'as' => 'affiliate'
    ]);

});