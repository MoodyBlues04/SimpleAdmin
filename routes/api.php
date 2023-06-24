<?php

use App\Http\Controllers\AuthController;
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

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/login', AuthController::class . '@login');
    Route::post('/register', UserController::class . '@register');
    Route::post('/logout', AuthController::class . '@logout');
    Route::post('/refresh', AuthController::class . '@refresh');
});
