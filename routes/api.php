<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Route::post('register', 'App\Http\Controllers\Api\AuthController@register');
Route::post('login', 'App\Http\Controllers\Api\AuthController@login');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'App\Http\Controllers\Api\AuthController@logout');
    Route::any('/user', function (Request $request) {
        return new UserResource(auth()->user());
    });

    Route::resource('events', 'App\Http\Controllers\Api\EventController');
    Route::get('{user_id}/events/{custom_link}', 'App\Http\Controllers\Api\EventController@showSchedule');
    Route::post('{user_id}/events/{event_id}/appointments', 'App\Http\Controllers\Api\AppointmentController@store');


});

