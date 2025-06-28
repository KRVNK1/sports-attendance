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
        Route::get('/athletes', [AthleteController::class, 'index'])->name('athletes.index');
        Route::get('/athletes/create', [AthleteController::class, 'create'])->name('athletes.create');
        Route::post('/athletes', [AthleteController::class, 'store'])->name('athletes.store');
        Route::get('/athletes/{athlete}/edit', [AthleteController::class, 'edit'])->name('athletes.edit');
        Route::put('/athletes/{athlete}', [AthleteController::class, 'update'])->name('athletes.update');
        Route::delete('/athletes/{athlete}', [AthleteController::class, 'destroy'])->name('athletes.destroy');

        // Тренеры
        Route::get('/coaches', [CoachController::class, 'index'])->name('coaches.index');
        Route::get('/coaches/create', [CoachController::class, 'create'])->name('coaches.create');
        Route::post('/coaches', [CoachController::class, 'store'])->name('coaches.store');
        Route::get('/coaches/{coach}/edit', [CoachController::class, 'edit'])->name('coaches.edit');
        Route::put('/coaches/{coach}', [CoachController::class, 'update'])->name('coaches.update');
        Route::delete('/coaches/{coach}', [CoachController::class, 'destroy'])->name('coaches.destroy');

        // Группы
        Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
        Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
        Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
        Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
        Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
        Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

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
    });

    // Маршруты для тренера
    Route::middleware(['coach'])->group(function () {
        // Спортсмены (только просмотр)
        Route::get('/coach/athletes', [AthleteController::class, 'index'])->name('coach.athletes.index');
        Route::get('/coach/athletes/{athlete}', [AthleteController::class, 'show'])->name('coach.athletes.show');

        // Группы (только просмотр своих групп)
        Route::get('/coach/groups', [GroupController::class, 'index'])->name('coach.groups.index');
        Route::get('/coach/groups/{group}', [GroupController::class, 'show'])->name('coach.groups.show');

        // Расписание (создание и редактирование своих тренировок)
        Route::get('/coach/schedule/create', [ScheduleController::class, 'create'])->name('coach.schedule.create');
        Route::post('/coach/schedule', [ScheduleController::class, 'store'])->name('coach.schedule.store');
        Route::get('/coach/schedule/{training}/edit', [ScheduleController::class, 'edit'])->name('coach.schedule.edit');
        Route::put('/coach/schedule/{training}', [ScheduleController::class, 'update'])->name('coach.schedule.update');
        Route::delete('/coach/schedule/{training}', [ScheduleController::class, 'destroy'])->name('coach.schedule.destroy');
        Route::patch('/coach/schedule/{training}/status', [ScheduleController::class, 'updateStatus'])->name('coach.schedule.updateStatus');

        // Посещаемость (отметка для своих тренировок)
        Route::get('/coach/attendance/training/{training}', [AttendanceController::class, 'markAttendance'])->name('coach.attendance.mark');
        Route::post('/coach/attendance/training/{training}', [AttendanceController::class, 'storeAttendance'])->name('coach.attendance.store');
        Route::get('/coach/attendance/training/{training}/show', [AttendanceController::class, 'showAttendance'])->name('coach.attendance.show');
    });
});
