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


Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');


Route::group(['middleware' => 'jwt.verify'], function () {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('get-transaction', 'TransactionController@getTransaction');
    Route::get('get-transaction/{id}', 'TransactionController@getTransactionById');
    Route::post('store-transaction', 'TransactionController@storeTransaction');
    Route::put('update-transaction/{id}', 'TransactionController@updateTransaction');
    Route::put('delete-transaction/{id}', 'TransactionController@deleteTransaction');
});