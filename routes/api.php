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
    "prefix" => "v1",
    "middleware" => "auth:api",
], function () {

    Route::group([
        "prefix" => "attendance",
    ], function () {

        Route::get("/", "AttendanceController@all");
        Route::post("create", "AttendanceController@create");
        Route::post("create/bulk", "AttendanceController@bulkScheduleInsertion");
        Route::post("create/bulk/excel", "AttendanceController@excelData");
        Route::post('delete/{attendance_id}', 'AttendanceController@delete');
        Route::get("fetch/{attendance_id}", "AttendanceController@fetch");
        Route::get('search', 'AttendanceController@search');
        Route::post('update/{attendance_id}', 'AttendanceController@update');

    });

    Route::group([
        "prefix" => "overtime",
    ], function () {

        Route::get("/", "OvertimeController@index"); // primary table function index
        Route::get("search", "OvertimeController@search"); // primary table function search
        Route::post("create", "OvertimeController@create"); // primary table function create
        Route::post("update/{id}", "OvertimeController@update"); // primary table function update
        Route::post("delete/{id}", "OvertimeController@delete"); // primary table function delete

        // secondary functions
        // Route::get("/searchAgent", "OvertimeController@searchAgent");
        // Route::get("agents", "OvertimeController@agents");

    });

    Route::group([
        "prefix" => "schedules",
    ], function () {

        Route::group([
            "prefix" => "overtime",
        ], function () {

            Route::get("/", "OvertimeController@all");
            Route::post("create", "OvertimeController@create");
            Route::post("create/bulk", "OvertimeController@bulkScheduleInsertion");
            Route::post('delete/{overtime_id}', 'OvertimeController@delete');
            Route::post('approve/{overtime_id}', 'OvertimeController@approve');
            Route::get('search', 'OvertimeController@search');
            Route::get('join', 'OvertimeController@join');

        });

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
        Route::get('work/{option}', 'AgentScheduleController@workInfo');
        Route::post('conformance/{id}', 'AgentScheduleController@conformance');
        Route::post('remarks/{id}', 'AgentScheduleController@remarks');

    });

    Route::group([
        "prefix" => "request_schedules",
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
        "prefix" => "access_levels",
    ], function () {

        //Route::get("/", "AgentController@all");
        Route::post("create", "AccessLevelHierarchyController@create");
        // Route::post('delete/{id}', 'AgentController@delete');
        //Route::get("fetch/{agent_id}", "AgentController@fetch");
        // Route::post('update/{id}', 'AgentController@update');
        //Route::get("search", "AgentController@search");

    });

    Route::group([
        "prefix" => "clusters",
    ], function () {

        //Route::get("/", "AgentController@all");
        Route::post("create", "ClusterController@create");
        // Route::post('delete/{id}', 'AgentController@delete');
        //Route::get("fetch/{agent_id}", "AgentController@fetch");
        // Route::post('update/{id}', 'AgentController@update');
        //Route::get("search", "AgentController@search");

    });

    Route::group([
        "prefix" => "agents",
    ], function () {

        Route::get("/", "AgentController@all");
        // Route::post("create", "AgentController@create");
        // Route::post('delete/{id}', 'AgentController@delete');
        Route::get("fetch/{agent_id}", "AgentController@fetch");
        // Route::post('update/{id}', 'AgentController@update');
        Route::get("search", "AgentController@search");

    });

    Route::group([
        "prefix" => "events",
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
        "prefix" => "leaves",
    ], function () {

        Route::get("/", "LeaveController@all");
        Route::post("create", "LeaveController@create");
        Route::post('delete/{leave_id}', 'LeaveController@delete');
        Route::get("fetch/{leave_id}", "LeaveController@fetch");
        Route::post('update/{leave_id}', 'LeaveController@update');
        Route::get('search', 'LeaveController@search');
        Route::post("{action}/{leave_id}", "LeaveController@approval")->where('action', 'approve|reject');
        Route::post("cancel/{leave_id}", "LeaveController@cancel");

        Route::group([
            "prefix" => "credits",
        ], function () {

            Route::get("/", "LeaveCreditController@all");
            Route::post("create", "LeaveCreditController@create");
            Route::post('delete/{leave_credit_id}', 'LeaveCreditController@delete');
            Route::get("fetch/{leave_credit_id}", "LeaveCreditController@fetch");
            Route::post('update/{leave_credit_id}', 'LeaveCreditController@update');
            Route::get('search', 'LeaveCreditController@search');

        });

        Route::group([
            "prefix" => "slots",
        ], function () {

            Route::get("/", "LeaveSlotController@all");
            Route::post("create", "LeaveSlotController@create");
            Route::post('delete/{leave_slot_id}', 'LeaveSlotController@delete');
            Route::get("fetch/{leave_slot_id}", "LeaveSlotController@fetch");
            Route::post('update/{leave_slot_id}', 'LeaveSlotController@update');
            Route::get('search', 'LeaveSlotController@search');
            Route::get('count', 'LeaveSlotController@count');

        });
    });

    Route::group([
        "prefix" => "logs",
    ], function () {

        Route::get("/", "LogsController@index");
        Route::get("fetch/{id}", "LogsController@log");
        Route::post("create", "LogsController@create");
        Route::get("search", "LogsController@search");

    });

    Route::group([
        "prefix" => "reports",
    ], function () {

        Route::get("/", "ReportsController@index");
        Route::get("issued_to/{id}", "ReportsController@report");
        Route::get("issued_by/{id}", "ReportsController@userFiledIR");
        Route::get("select_all_users/{id}", "ReportsController@getSelectAllUserUnder");
        Route::get("IR", "ReportsController@getAll_Ir");
        Route::get("all_users", "ReportsController@getAllUser");
        Route::get("all_users/{id}", "ReportsController@getAllUserUnder");
        Route::post("create", "ReportsController@create");
        Route::post('update/{ir_id}', 'ReportsController@update');
        Route::post('delete/{ir_id}', 'ReportsController@delete');
        Route::post("user_response", "ReportsController@userResponse");
        Route::post('update_response/{id}', 'ReportsController@update_response');
        Route::get("issuedto/search", "ReportsController@reportSearch");
        Route::get("issuedby/search", "ReportsController@userFiledIRSearch");
        //Route::get("ir/search", "ReportsController@getAll_IrSearch");

    });

    Route::group([
        "prefix" => "sanction_type",
    ], function () {

        Route::get("select_sanction_types", "ReportsController@getSanctionType");
        Route::get("/", "ReportsController@getSanctionTypes");
        Route::get("search", "ReportsController@getSanctionTypesSearch");
        Route::post('delete/{id}', 'ReportsController@delete_stype');
        Route::post('update/{id}', 'ReportsController@update_stype');
        Route::post("create", "ReportsController@addSanctionType");
    });
    Route::group([
        "prefix" => "sanction_level",
    ], function () {
        Route::get("select_sanction_levels", "ReportsController@getSanctionLevel");
        Route::get("/", "ReportsController@getSanctionLevels");
        Route::get("search", "ReportsController@getSanctionLevelsSearch");
        Route::post('delete/{id}', 'ReportsController@delete_slevel');
        Route::post('update/{id}', 'ReportsController@update_slevel');
        Route::post("create", "ReportsController@addSanctionLevel");
    });

    Route::group([
        "prefix" => "users",
    ], function () {

        Route::get("/", "UserController@usersInfo");
        Route::get("fetch/{id}", "UserController@userInfo");
        Route::get("logged_user", "UserController@userInfoLogged");
        Route::get("access_levels", "UserController@accessLevel");
        Route::get("status_list", "UserController@statusList");
        Route::get("cluster/{id}", "UserController@getCluster");
        Route::post("change_status", "UserController@updateStatus");
        Route::post("add_user_status", "UserController@addStatus");
        Route::post("bulk_change_status", "UserController@bulkUpdateStatus");
        Route::post("create", "UserController@addUser");
        Route::post("update/{id}", "UserController@updateUser");
        Route::post("change_pass/{id}", "UserController@changePass");
        Route::post("reset_pass/{id}", "UserController@resetPass");
        Route::get("search", "UserController@search");
        Route::post("excel_to_array", "UserController@excelImportuser");
        Route::post("import_user", "UserController@addUserImport");
        Route::post("update_user_status/{id}", "UserController@updateUserStatus");
        Route::post("delete_user_status/{id}", "UserController@deleteUserStatus");


    });

    Route::group([
        "prefix" => "notifications",
    ], function () {

        Route::get("/", "NotificationController@fetchAll");
        Route::post("read/{notification_id}", "NotificationController@readNotification");
        Route::get("fetch/{notification_id}", "NotificationController@fetchNotification");
        Route::get("unread", "NotificationController@fetchUnread");
        Route::post("update/{notification_id}", "NotificationController@update");
        // for future cron jobs
        Route::get("scheduled", "NotificationController@scheduledNotifications");
    });

    Route::group([
        "prefix" => "excel",
    ], function () {
        Route::get('export_report', 'excelController@report');
        Route::get('export_add_template', 'excelController@Addtemplate');
        Route::get('reassign_template', 'excelController@Reassigntemplate');
        Route::post('import_to_array', 'excelController@importToArray');
        Route::post('import', 'excelController@importStoreAdd');
    });
});

Route::get("/", function () { //test lang kung working
    return 'ano yan kapatid?';
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Unauthorized action. If error persists, contact bfjax5@gmail.com'], 404);
});
