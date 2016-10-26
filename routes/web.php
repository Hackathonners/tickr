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

// Auth Routes
Router::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Router::post('login', 'Auth\LoginController@login');
Router::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// Router::get('register', 'Auth\RegisterController@showRegistrationForm');
// Router::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Router::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Router::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Router::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Router::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('{all}', function () {
    return view('app');
})->where('all', '(.*)')->middleware('auth');
