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
        Route::get("search", "AgentController@search");

    });

    Route::group([
        "prefix"    => "events",
    ], function () {

        Route::get("/", "EventTitleController@all");
        Route::get("/select", "EventTitleController@select");
        Route::post("create", "EventTitleController@create");
        Route::post('delete/{event_id}', 'EventTitleController@delete');
        Route::get("fetch/{event_id}", "EventTitleController@fetch");
        Route::post('update/{event_id}', 'EventTitleController@update');
        Route::get('search', 'EventTitleController@search');

    });

    Route::group([
        "prefix"    => "logs",
    ], function () {

        Route::get("/", "LogsController@index");
        Route::get("user/{id}", "LogsController@log");
        Route::post("create", "LogsController@create");
    });

    Route::group([
        "prefix"    => "reports",
    ], function () {

        Route::get("/", "ReportsController@index");
        Route::get("user/{id}", "ReportsController@report");
        Route::get("user_filed_ir/{id}", "ReportsController@userFiledIR");
        Route::get("select_sanction_types", "ReportsController@getSanctionType");
        Route::get("select_sanction_levels", "ReportsController@getSanctionLevel");
        Route::get("select_all_users/{id}", "ReportsController@getSelectAllUserUnder");
        Route::get("sanction_types", "ReportsController@getSanctionTypes");
        Route::get("sanction_levels", "ReportsController@getSanctionLevels");
        Route::get("all_users", "ReportsController@getAllUser");
        Route::get("all_users/{id}", "ReportsController@getAllUserUnder");
        Route::post("create", "ReportsController@create");
        Route::post("add_sanction_type", "ReportsController@addSanctionType");
        Route::post("add_sanction_level", "ReportsController@addSanctionLevel");
        Route::post("user_reponse", "ReportsController@userResponse");
    });

     Route::group([
        "prefix"    => "users",
    ], function () {

        Route::get("/", "UserController@usersInfo");
        Route::get("/{id}", "UserController@userInfo");
    });




});


Route::get("/", function(){ //test lang kung working
    return 'ano yan kapatid?';
});