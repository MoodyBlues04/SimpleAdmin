<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\RegisterController;
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

// registration
Route::post('/signup', RegisterController::class . '@signUp');
Route::post('/login', RegisterController::class . '@logIn');
Route::post('/logout', RegisterController::class . '@logOut'); // ?

// api_token
Route::post('/api_token', ApiTokenController::class . '@refresh');
