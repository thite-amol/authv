<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    } else {
        return redirect('/login');
    }
});

Auth::routes();

Authv::routes();

Route::get('/home', 'HomeController@index');

// temp added
Route::get('user/activation/{token}', 'Auth\LoginController@activateUser')->name('user.activate');
