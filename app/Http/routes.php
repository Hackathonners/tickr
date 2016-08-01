<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Auth::login(\App\Karina\User::first());
Route::get('/', function(){
    return view('welcome');
});

Route::group(['prefix' => 'api/v1'], function () {
    Route::resource('events', 'Api\EventsController');
    Route::resource('guestlists', 'Api\GuestListsController');

    Route::post('events/{eventId}/registrations', 'Api\RegistrationsController@store');
    Route::post('registrations/{id}/activate', 'Api\RegistrationsController@activate');
});
