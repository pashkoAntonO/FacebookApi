<?php


Route::get('/', function () {
    return view('Facebook/Auth');
});



Route::group(['namespace' => 'FaceBook'], function () {
    Route::get('/login', 'AuthController@getToken')->name('login');
    Route::get('/account', 'PostsController@posts')->name('account');
    Route::get('/show', 'PostsController@show')->name('show');
    Route::get('/session', 'AuthController@setSession')->name('session');
});


