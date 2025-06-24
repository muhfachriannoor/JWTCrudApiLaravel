<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HobbyController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route yang memerlukan autentikasi JWT
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    // CRUD User API
    Route::apiResource('users', UserController::class);

    Route::get('users/{user}/hobby', [HobbyController::class, 'index']);
    Route::post('users/{user}/hobby', [HobbyController::class, 'store']);
    Route::delete('hobby/{hobi}', [HobbyController::class, 'destroy']);
});