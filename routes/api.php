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

        Route::get("/", "AttendanceController@all");

    });

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
        Route::get('stats', 'AgentScheduleController@stats');

    });

    
    Route::group([
        "prefix"    => "request_schedules",
    ], function () {

        Route::get("/", "RequestScheduleController@all");
        Route::post("create", "RequestScheduleController@create");
        Route::post('delete/{request_schedule_id}', 'RequestScheduleController@delete');
        Route::get("fetch/{request_schedule_id}", "RequestScheduleController@fetch");
        Route::post('update/{request_schedule_id}', 'RequestScheduleController@update');
        Route::get('search', 'RequestScheduleController@search');

        Route::get("applicant/{applicant_id}", "RequestScheduleController@fetchByApplicant");
        Route::get("requested_by/{requested_by_id}", "RequestScheduleController@fetchByRequestedBy");
        Route::get("managed_by/{managed_by_id}", "RequestScheduleController@fetchByManagedBy");

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
        Route::get("/{id}", "LogsController@log");
        Route::post("create", "LogsController@create");
    });

    Route::group([
        "prefix"    => "reports",
    ], function () {

        Route::get("/", "ReportsController@index");
        Route::get("issued_to/{id}", "ReportsController@report");
        Route::get("issued_by/{id}", "ReportsController@userFiledIR");
        Route::get("select_all_users/{id}", "ReportsController@getSelectAllUserUnder");
        Route::get("all_users", "ReportsController@getAllUser");
        Route::get("all_users/{id}", "ReportsController@getAllUserUnder");
        Route::post("create", "ReportsController@create");
        Route::post('update/{ir_id}', 'ReportsController@update');
        Route::post('delete/{ir_id}', 'ReportsController@delete');       
        Route::post("user_reponse", "ReportsController@userResponse");
        Route::post('update_response/{id}', 'ReportsController@update_response');
    });

    Route::group([
        "prefix"    => "sanction_type",
    ], function () {

        Route::get("select_sanction_types", "ReportsController@getSanctionType");
        Route::get("sanction_types", "ReportsController@getSanctionTypes");
        Route::post('delete/{id}', 'ReportsController@delete_stype');
        Route::post('update/{id}', 'ReportsController@update_stype');
        Route::post("create", "ReportsController@addSanctionType");
    });
    Route::group([
        "prefix"    => "sanction_level",
    ], function () {
        Route::get("select_sanction_levels", "ReportsController@getSanctionLevel");
        Route::get("sanction_levels", "ReportsController@getSanctionLevels");
        Route::post('delete/{id}', 'ReportsController@delete_slevel');
        Route::post('update/{id}', 'ReportsController@update_slevel');
        Route::post("create", "ReportsController@addSanctionLevel");
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