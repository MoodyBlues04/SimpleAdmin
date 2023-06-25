<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// TODO to files

// auth
Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::post('/login', AuthController::class . '@login')->name('login');
    Route::post('/logout', AuthController::class . '@logout')->name('logout');
    Route::post('/refresh', AuthController::class . '@refresh')->name('refresh');
});

// user
Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::get('/user', UserController::class . '@index')->name('user.index');
    Route::get('/user/{user}', UserController::class . '@show')->name('user.show');
    Route::post('/user', UserController::class . '@store')->name('user.store');
});

// events
Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::get('/event', EventController::class . '@index')->name('event.index');
    Route::get('/event/{event}', EventController::class . '@show')->name('event.show'); // TODO create & edit на фронте
    Route::post('/event', EventController::class . '@store')->name('event.store');
    Route::put('/event/{event}', EventController::class . '@update')->name('event.update');
    Route::delete('/event/{event}', EventController::class . '@destroy')->name('event.destroy');
    Route::post('/event/join/{event}', EventController::class . '@join')->name('event.join');
    Route::post('/event/cancel/{event}', EventController::class . '@cancel')->name('event.cancel');
});
