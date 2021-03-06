<?php

Route::namespace('Api')->prefix('v1')->middleware('cors')->group(function () {
    Route::get('/users', 'UserController@index')->name('users.index');
    Route::post('/users', 'UserController@store')->name('users.store');
    Route::post('/login', 'UserController@login')->name('users.login');
    Route::get('/users/{user}', 'UserController@show')->name('users.show');
    // Route::apiResource('users', 'UserController');
});
