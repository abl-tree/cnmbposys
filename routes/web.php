<?php

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Auth::routes();

/*
|------------------------------------------------------------------------------------
| Admin
|------------------------------------------------------------------------------------
*/
Route::group([ 'middleware'=>['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::resource('users', 'UserController');
    /*Route::get('/email', function () {
        return view('admin.dashboard.email');
    });*/
    
    Route::get('/forms', function () {
        return view('admin.dashboard.forms');
    });

    //PROFILE -- START

    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/refreshEmployeeList', 'ProfileController@refreshEmployeeList');
    Route::get('/updateEmployeeList', 'ProfileController@updateEmployeeList');
    Route::get('/viewProfile', 'ProfileController@viewProfile');
    Route::get('/getCurrentProfile', 'ProfileController@getCurrentProfile');
    Route::get('/getCurrentTab', 'ProfileController@getCurrentTab');
    Route::get('/childView', 'ProfileController@childView');
    Route::get('/terminatedView', 'ProfileController@terminatedView');

    //PROFILE -- END

    //CU EMPLOYEE -- START

    Route::resource('employee','EmployeeController');
    Route::post('employee/fetch','EmployeeController@fetch');
    Route::post('employee/fetch_employee_data','EmployeeController@fetch_employee_data');
    Route::post('employee/fetch_blob_image','EmployeeController@fetch_blob_image');

    //CU EMPLOYEE -- END


    //Incident Report
    Route::post('/add_IR', 'UserController@add_IR')->name('add_IR');
    // end of Incident Report routes
    
    //Email Route
    Route::get('sendEmail','UserController@sendMail')->name('sendMail');

    //UPDATE STATUS -- START
    Route::post('/update_status', 'EmployeeController@update_status');
    Route::get('/get_status', 'EmployeeController@get_status');
    //UPDATE STATUS -- END

    //IMPORT EXPORT EXCEL -- START
    Route::get('/profile/excel_exportreport','excelController@report')->name('excel.exportreport');
    Route::get('/profile/excel_exporttemplate','excelController@template')->name('excel.exporttemplate');
    Route::post('/profile/excel_import','excelController@import')->name('excel.import');
    //IMPORT EXPORT EXCEL -- END
});
