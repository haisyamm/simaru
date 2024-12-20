<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\RoomController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::resource('users', UserController::class);
    Route::resource('rooms', RoomController::class);
});
