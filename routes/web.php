<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Главная страница (расписание)
Route::get('/', [ScheduleController::class, 'index'])->name('welcome');

// Маршруты аутентификации
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Маршруты, требующие аутентификации
Route::middleware(['auth'])->group(function () {
    // Спортсмены
    Route::resource('athletes', AthleteController::class);
    
    // Тренеры
    Route::resource('coaches', CoachController::class);
    
    // Группы
    Route::resource('groups', GroupController::class);
    
    // Расписание
    Route::resource('schedule', ScheduleController::class);
});