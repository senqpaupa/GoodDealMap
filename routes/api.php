<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;

// Аутентификация
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// Маршруты для работы с пользователем
Route::prefix('user')->group(function () {
    Route::get('/userinfo', [UserController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/avatar', [UserController::class, 'updateAvatar']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
});

// Маршруты для запросов
Route::get('/requests/nearby', [RequestController::class, 'nearby']);
Route::apiResource('requests', RequestController::class);