<?php

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Auth::routes();

/*
|------------------------------------------------------------------------------------
| Admin
|------------------------------------------------------------------------------------
*/

Route::group([ 'middleware'=>['firstLogin']], function () {
    Route::get('/security', 'firstLoginController@index')->name('security');
    Route::post('/updatePass', 'firstLoginController@updatePassword')->name('updatePass');
});

Route::group([ 'middleware'=>['loginVerif']], function () {

    // Route::get('/', 'ProfileController@index')->name('dashboard');
    Route::resource('users', 'UserController');
    /*Route::get('/email', function () {
        return view('admin.dashboard.email');
    });*/
    
//////////////////////////////////// PAGE ROUTES
//admin
Route::get('/todays_activity', 'pageController@rtadashboard')->name('rtadashboard');
Route::get('/', 'pageController@dashboard')->name('dashboard');
Route::get('/dashboard', 'pageController@dashboard')->name('dashboard');
Route::get('/agent_schedules', 'pageController@schedule')->name('schedule');
Route::get('/incident_reports', 'pageController@incident_report')->name('incident_report');
Route::get('/work_reports', 'pageController@rtareport')->name('rtareport');
Route::get('/leave_requests', 'pageController@rtaeventrequest')->name('rtaevent_request');
Route::get('/action_logs', 'pageController@action_logs')->name('action_logs');
Route::get('/agent', 'pageController@agent')->name('agent');
    
    Route::get('/rtaschedule', 'pageController@rtaschedule')->name('rtaschedule');
    Route::get('/rtareport', 'pageController@rtareport')->name('rtareport');
    
    Route::get('/tldashboard', 'pageController@tldashboard')->name('tldashboard');
    Route::get('/tlreport', 'pageController@tlreport')->name('tlreport');













// END PAGE ROUTES ///////////////////////////////




    Route::get('/forms', function () {
        return view('admin.dashboard.forms');
    });

    //PROFILE -- START

    Route::get('/logstat', 'ProfileController@checkLoginStat')->name('logstat');

    Route::get('/refreshEmployeeList', 'ProfileController@refreshEmployeeList');
    Route::get('/updateEmployeeList/{id}', 'ProfileController@updateEmployeeList');
    Route::get('/viewProfile', 'ProfileController@viewProfile');
    Route::get('/backToProfile', 'ProfileController@backToProfile');
    Route::get('/getCurrentProfile', 'ProfileController@getCurrentProfile');
    Route::get('/getCurrentTab', 'ProfileController@getCurrentTab');
    Route::get('/childView', 'ProfileController@childView');
    Route::get('/terminatedView', 'ProfileController@terminatedView');

    //PROFILE -- END

    //CU EMPLOYEE -- START

    Route::resource('employee','EmployeeController');
    Route::post('employee/fetch','EmployeeController@fetch');
    Route::post('employee/fetch_employee_data','EmployeeController@fetch_employee_data');

    //CU EMPLOYEE -- END


    //Incident Report
    Route::post('/add_IR', 'UserController@add_IR')->name('add_IR');
    Route::get('/get_ir', 'UserController@get_ir')->name('get_ir');
    // end of Incident Report routes
    
    //Email Route
    Route::get('sendEmail','UserController@sendMail')->name('sendMail');

    //UPDATE STATUS -- START
    Route::post('/update_status', 'EmployeeController@update_status');
    Route::get('/get_status', 'EmployeeController@get_status');
    //UPDATE STATUS -- END

    //ADD POSITION -- START
    Route::post('/add_position', 'EmployeeController@add_position');
    Route::get('/get_position/{option?}', 'EmployeeController@get_position');
    //ADD POSITION -- END

    //IMPORT EXPORT EXCEL -- START
    Route::get('/profile/excel_export_report','excelController@report')->name('excel.exportreport');
    Route::get('/profile/excel_export_add_template','excelController@Addtemplate')->name('excel.exportaddtemplate');
    Route::get('/profile/excel_export_reassign_template','excelController@Reassigntemplate')->name('excel.exportreassigntemplate');
    Route::post('/excel/import/toarray','excelController@importToArray'); 
    Route::post('/excel/import/store/add','excelController@importStoreAdd');
    //IMPORT EXPORT EXCEL -- END
});