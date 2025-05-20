<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
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
    // Профиль спортсмена
    Route::get('/profile', [AthleteController::class, 'profile'])->name('profile');
    
    // Маршруты для администратора
    Route::middleware(['auth'])->group(function () {
        // Спортсмены
        Route::resource('athletes', AthleteController::class);
        
        // Тренеры
        Route::resource('coaches', CoachController::class);
        
        // Группы
        Route::resource('groups', GroupController::class);
        
        // Расписание
        Route::resource('schedule', ScheduleController::class);
        
        // Посещаемость
        // Route::resource('attendance', AttendanceController::class);
    });
    
    // Маршруты для тренера
    Route::middleware(['auth'])->group(function () {
        // Спортсмены (только просмотр)
        Route::get('/athletes', [AthleteController::class, 'index'])->name('athletes.index');
        Route::get('/athletes/{athlete}', [AthleteController::class, 'show'])->name('athletes.show');
        
        // Группы (только просмотр своих групп)
        Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
        
        // Расписание (просмотр и редактирование своих тренировок)
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
        Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
        Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::get('/schedule/{training}/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');
        Route::put('/schedule/{training}', [ScheduleController::class, 'update'])->name('schedule.update');
        
        // Посещаемость (отметка присутствия)
        // Route::get('/attendance/group/{group}', [AttendanceController::class, 'groupAttendance'])->name('attendance.group');
        // Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    });
    
    // Маршруты для спортсмена
        // Расписание (только просмотр)
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

});