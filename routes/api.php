<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\OtpPasswordController;


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('password/otp/request', [OtpPasswordController::class, 'requestOtp']);
    Route::post('password/otp/reset', [OtpPasswordController::class, 'resetWithOtp']);
    // Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});
