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
    $user = $request->user();
    return [
        'user' => $user,
        'roles' => $user->getRoleNames()
    ];
});

Route::get('products', 'ProductController@lists');
Route::post('products', 'ProductController@store');
Route::post('products/{id}', 'ProductController@update');
Route::delete('products/{id}', 'ProductController@destroy');
