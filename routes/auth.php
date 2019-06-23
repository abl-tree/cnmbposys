<?php

Route::group([
    "namespace" => "Auth",
], function () {

    Route::post("login", "LoginController@loginPassport");
    Route::middleware("auth:api")->get("user", "TokenController@getUser");
    Route::middleware("auth:api")->get('logout', "TokenController@revokeToken");
});
