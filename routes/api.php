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

// Route::group([
//     "namespace" => "Sample",
//     "prefix"    => "sample",
// ], function () {

//     Route::get("/", "SampleController@all");
//     Route::get("search", "SampleController@search");
//     Route::post("create", "SampleController@create");
//     Route::get("fetch/{id}", "SampleController@fetch");
//     Route::post('update/{id}', 'SampleController@update');
//     Route::post('delete/{id}', 'SampleController@delete');

// });
