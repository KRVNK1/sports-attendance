<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;

// Главная страница (расписание)
Route::get('/', [ScheduleController::class, 'index'])->name('welcome');
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

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
    Route::middleware(['admin'])->group(function () {
        // Спортсмены
        Route::resource('athletes', AthleteController::class);

        // Тренеры
        Route::resource('coaches', CoachController::class);

        // Группы
        Route::resource('groups', GroupController::class);


        // Расписание (полный доступ)
        Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
        Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::get('/schedule/{training}/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');
        Route::put('/schedule/{training}', [ScheduleController::class, 'update'])->name('schedule.update');
        Route::delete('/schedule/{training}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
        Route::patch('/schedule/{training}/status', [ScheduleController::class, 'updateStatus'])->name('schedule.updateStatus');

        // Посещаемость (полный доступ)
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/training/{training}', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
        Route::post('/attendance/training/{training}', [AttendanceController::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/attendance/training/{training}/show', [AttendanceController::class, 'showAttendance'])->name('attendance.show');
        Route::patch('/schedule/{training}/status', [ScheduleController::class, 'updateStatus'])->name('schedule.updateStatus');
    });

    // Маршруты для тренера
    Route::middleware(['auth'])->group(function () {
        // Спортсмены (только просмотр)
        Route::get('/athletes', [AthleteController::class, 'index'])->name('athletes.index');
        Route::get('/athletes/{athlete}', [AthleteController::class, 'show'])->name('athletes.show');

        // Группы (только просмотр своих групп)
        Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');

        // Расписание (создание и редактирование своих тренировок)
        Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
        Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::get('/schedule/{training}/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');
        Route::put('/schedule/{training}', [ScheduleController::class, 'update'])->name('schedule.update');
        Route::delete('/schedule/{training}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
        Route::patch('/schedule/{training}/status', [ScheduleController::class, 'updateStatus'])->name('schedule.updateStatus');

        // Посещаемость (отметка для своих тренировок)
        Route::get('/attendance/training/{training}', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
        Route::post('/attendance/training/{training}', [AttendanceController::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/attendance/training/{training}/show', [AttendanceController::class, 'showAttendance'])->name('attendance.show');
    });

    // Маршруты для спортсмена
    // Расписание (только просмотр)
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
});
