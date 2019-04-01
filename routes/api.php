<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('signup', 'Auth\AuthController@signup');
    Route::get('signup', 'Auth\AuthController@create');
    Route::post('password/email', 'Auth\ForgotPasswordController@getResetToken');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Auth\AuthController@logout');
        Route::get('user', 'Auth\AuthController@user');
    });
});

Route::group(['middleware' => 'auth:api'], function ($router) {
   
    Route::post('user/', 'UserController@store');
    Route::get('user/list', 'UserController@list');
    Route::get('user/create', 'Auth\AuthController@create');
    Route::get('user/{id}', 'UserController@show');
    Route::put('user/{id}/update', 'UserController@update');
    Route::delete('user/{id}/destroy', 'UserController@destroy');
    
    Route::get('teacher/list', 'TeacherController@list');
    Route::get('teacher/list/favorite', 'TeacherController@listFavorites');
    Route::get('teacher/{id}', 'TeacherController@show');
    
    Route::post('teacher/favorite', 'TeacherController@favorite');
    
    Route::get('function/primary/{rol}', 'FunctionsController@primaryMenu');
    Route::get('function/secondary/{rol}', 'FunctionsController@secondaryMenu');

});

Route::get('migrate',  function (){ 
    return Artisan::call('migrate:refresh', ['--seed' => true,]); 
});
Route::get('cache',  function (){ 
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return Artisan::call('cache:clear');
});
