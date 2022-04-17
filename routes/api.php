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

Route::post('/login', 'LoginController@index');
Route::post('/users', 'UserController@create');

Route::middleware('auth.custom:api')->group(function () {
    Route::get('/users', 'UserController@index');
    Route::put('/users/{id}', 'UserController@edit');
});



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
