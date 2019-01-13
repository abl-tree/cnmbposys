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

Route::group([
    "prefix"    => "v1",
], function () {


    Route::group([
        "prefix"    => "schedules",
    ], function () {

        Route::get("/", "AgentScheduleController@all");
        Route::get('agents', 'AgentScheduleController@fetchAllAgentsWithSchedule');
        Route::get('agents/{agent_id}', 'AgentScheduleController@fetchAgentWithSchedule');
        Route::post("create", "AgentScheduleController@create");
        Route::post("create/bulk", "AgentScheduleController@bulkScheduleInsertion");
        Route::post("create/bulk/excel", "AgentScheduleController@excelData");
        Route::post('delete/{schedule_id}', 'AgentScheduleController@delete');
        Route::get("fetch/{schedule_id}", "AgentScheduleController@fetch");
        Route::post('update/{schedule_id}', 'AgentScheduleController@update');
        Route::get('search', 'AgentScheduleController@search');

    });

    Route::group([
        "prefix"    => "agents",
    ], function () {

        Route::get("/", "AgentController@all");
        // Route::post("create", "AgentController@create");
        // Route::post('delete/{id}', 'AgentController@delete');
        Route::get("fetch/{agent_id}", "AgentController@fetch");
        // Route::post('update/{id}', 'AgentController@update');

    });

    Route::group([
        "prefix"    => "events",
    ], function () {

        Route::get("/", "EventTitleController@all");
        Route::post("create", "EventTitleController@create");
        Route::post('delete/{schedule_id}', 'EventTitleController@delete');
        Route::get("fetch/{schedule_id}", "EventTitleController@fetch");
        Route::post('update/{schedule_id}', 'EventTitleController@update');

    });


});


Route::get("/", function(){ //test lang kung working
    return 'ano yan kapatid?';
});