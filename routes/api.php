<?php
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RequestController;

// Маршруты аутентификации
Route::post('/register', [RegisterController::class, 'register']); // Регистрация нового пользователя
Route::post('/login', [LoginController::class, 'login']); // Вход в систему
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']); // Выход из системы

// Маршруты для работы с запросами (требуют аутентификации)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('requests/nearby', [RequestController::class, 'nearby']); // Сначала этот маршрут
    Route::apiResource('requests', RequestController::class); // Потом ресурсные маршруты
});