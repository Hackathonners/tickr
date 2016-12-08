<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () {
    Route::resource('events', 'Api\EventsController');
    Route::get('events/{eventId}/stats', 'Api\EventsController@showStats');

    Route::resource('guestlists', 'Api\GuestListsController');
    Route::resource('guests', 'Api\GuestsController', [
            'only' => ['index'],
        ]);

    Route::post('events/{eventId}/registrations', 'Api\RegistrationsController@store');
    Route::post('registrations/{hashId}/activate/{token}', [
        'uses' => 'Api\RegistrationsController@activate',
        'as' => 'registrations.activate',
    ]);
    Route::post('registrations/{hashId}/resend', [
        'uses' => 'Api\RegistrationsController@resendEmail',
        'as' => 'registrations.resendEmail',
    ]);

    Route::get('users/{id}/registrations', 'Api\RegistrationsController@registry');
    Route::get('events/{eventId}/registrations', 'Api\RegistrationsController@index');

    Route::resource('registrations', 'Api\RegistrationsController', [
        'only' => ['destroy'],
    ]);

    Route::resource('users', 'Api\UsersController', [
        'only' => ['show'],
    ]);

    // 404 on not found routes under API group
    Route::get('{all}', function () {
        return (new App\Http\Controllers\Api\ApiController())->errorNotFound('Sorry, the resource you are looking for could not be found.');
    })->where('all', '(.*)');
});
