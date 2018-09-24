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

    //PROFILE -- END

    //CU EMPLOYEE -- START

    Route::resource('employee','EmployeeController');
    Route::post('employee/fetch','EmployeeController@fetch');
    Route::post('employee/fetch_employee_data','EmployeeController@fetch_employee_data');
    Route::post('employee/fetch_blob_image','EmployeeController@fetch_blob_image');

    //CU EMPLOYEE -- END

});
