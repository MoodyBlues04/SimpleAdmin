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

// 
Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/login', AuthController::class . '@login')->name('login');
    Route::post('/register', UserController::class . '@register')->name('register');
    Route::post('/logout', AuthController::class . '@logout')->name('logout');
    Route::post('/refresh', AuthController::class . '@refresh')->name('refresh');
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('/event', EventController::class . '@index')->name('event.index');
    // Route::get('/event/create', EventController::class . '@create')->name('event.create'); на фронте - здесь не нужно
    Route::post('/event', EventController::class . '@store')->name('event.store');
    Route::get('/event/{event}', EventController::class . '@show')->name('event.show');
    // Route::get('/event/{event}/edit', EventController::class . '@edit')->name('event.edit');
    Route::put('/event/{event}', EventController::class . '@update')->name('event.update');
    Route::delete('/event/{event}', EventController::class . '@destroy')->name('event.destroy');
});