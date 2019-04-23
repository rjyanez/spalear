<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login'         , 'Auth\AuthController@login'                  )                        ;
    Route::post('signup'        , 'Auth\AuthController@signup'                 )                        ;
    Route::get ('signup'        , 'Auth\AuthController@create'                 )                        ;
    Route::post('password/email', 'Auth\ForgotPasswordController@getResetToken')                        ;
    Route::post('password/reset', 'Auth\ResetPasswordController@reset'         )                        ;
    Route::get ('unique/{email}', 'UserController@unique'                      )                        ;
    Route::get ('logout'        , 'Auth\AuthController@logout'                 )->middleware('auth:api');
    Route::get ('user'          , 'Auth\AuthController@user'                   )->middleware('auth:api');
});

Route::group(['prefix' => 'password'], function () {
    Route::put ('reset'   , 'PasswordController@reset'                );
    Route::post('forgot'  , 'PasswordController@forgot'               );
    Route::post('current' , 'PasswordController@current'              );
    Route::post('validate', 'PasswordController@validateReminderToken');
});

Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'user'], function () {
        Route::post  ('/'             , 'UserController@store'      );
        Route::get   ('list'          , 'UserController@list'       );
        Route::get   ('create'        , 'Auth\AuthController@create');
        Route::get   ('{id}'          , 'UserController@show'       );
        Route::get   ('{id}/roles'    , 'UserController@roles'      );
        Route::get   ('{id}/progress' , 'UserController@progress'   );
        Route::put   ('{id}/update'   , 'UserController@update'     );
        Route::delete('{id}/destroy'  , 'UserController@destroy'    );
        Route::get   ('unique/{email}', 'UserController@unique'     );
    });

    Route::group(['prefix' => 'teacher'], function () {
        Route::get ('list'           , 'TeacherController@list'          );
        Route::get ('list/favorite'  , 'TeacherController@listFavorites' );
        Route::post('message'        , 'TeacherController@message'       );
        Route::post('favorite'       , 'TeacherController@favorite'      );
        Route::post('ranking'        , 'TeacherController@ranking'       );
        Route::get ('{id}/dashboard' , 'TeacherController@dashboard'     );
        Route::get ('{id}'           , 'TeacherController@show'          );
    });
    
    Route::group(['prefix' => 'student'], function () {
        Route::post('message', 'StudentController@message');
        Route::get ('list'   , 'StudentController@list'   );
        Route::post('sort'   , 'StudentController@sort'   );
        Route::post('level'  , 'StudentController@level'  );
        Route::post('message', 'StudentController@message');
        Route::get ('{id}'   , 'StudentController@show'   );
        Route::get ('{id}/short'   , 'StudentController@showShort'   );

    });

    Route::group(['prefix' => 'function'], function () {
        Route::get('primary/{rol}'  , 'FunctionsController@primaryMenu'  );
        Route::get('secondary/{rol}', 'FunctionsController@secondaryMenu');
    });

    Route::group(['prefix' => 'lesson'], function () {
        Route::get('{level}', 'LessonsController@list');
    });

    Route::group(['prefix' => 'meeting'], function () {
        Route::post  ('/'           , 'MeetingController@store'  );
        Route::delete('destroy/{id}', 'MeetingController@destroy');
    });

    Route::group(['prefix' => 'notification'], function () {
        Route::get('unread/{id}', 'NotificationController@unread'    );
        Route::get('mark/{id}'  , 'NotificationController@markAsRead');
    });
});

Route::get('migrate',  function () {
    return Artisan::call('migrate:refresh', ['--seed' => true,]);
});

Route::get('cache',  function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return Artisan::call('cache:clear');
});
