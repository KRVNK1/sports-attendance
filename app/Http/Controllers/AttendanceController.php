<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Training;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Показать статистику посещаемости по всем группам (только для админа)
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Только администратор может просматривать общую статистику');
        }

        // Получаем все группы с тренировками и посещаемостью
        $groups = Group::with(['coach', 'athletes'])
            ->withCount(['trainings' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->get();

        return view('attendance.index', compact('groups'));
    }

    /**
     * Показать форму для отметки посещаемости конкретной тренировки
     */
    public function markAttendance(Training $training)
    {
        $user = Auth::user();

        // Проверяем права доступа
        if (!$user->isAdmin() && !$user->isCoach()) {
            abort(403, 'У вас нет прав для отметки посещаемости');
        }

        // Если пользователь тренер, проверяем, что это его тренировка
        if ($user->isCoach() && $training->group->coach_id !== $user->id) {
            abort(403, 'Вы можете отмечать посещаемость только для своих тренировок');
        }

        // Получаем всех спортсменов группы
        $athletes = $training->group->athletes;

        // Получаем уже отмеченную посещаемость
        $existingAttendance = Attendance::where('training_id', $training->id)
            ->pluck('present', 'athlete_id')
            ->toArray();

        return view('attendance.mark', compact('training', 'athletes', 'existingAttendance'));
    }

    /**
     * Сохранить отметки посещаемости
     */
    public function storeAttendance(Request $request, Training $training)
    {
        $user = Auth::user();

        // Проверяем права доступа
        if (!$user->isAdmin() && !$user->isCoach()) {
            abort(403, 'У вас нет прав для отметки посещаемости');
        }

        // Если пользователь тренер, проверяем, что это его тренировка
        if ($user->isCoach() && $training->group->coach_id !== $user->id) {
            abort(403, 'Вы можете отмечать посещаемость только для своих тренировок');
        }

        $request->validate([
            'attendance' => 'array',
            'attendance.*' => 'boolean'
        ]);

        // Получаем всех спортсменов группы
        $athletes = $training->group->athletes;

        foreach ($athletes as $athlete) {
            $present = isset($request->attendance[$athlete->id]) ? true : false;

            // Обновляем или создаем запись о посещаемости
            Attendance::updateOrCreate(
                [
                    'training_id' => $training->id,
                    'athlete_id' => $athlete->id
                ],
                [
                    'present' => $present
                ]
            );
        }

        return redirect()->route('schedule.index')
            ->with('success', 'Посещаемость успешно отмечена');
    }

    /**
     * Показать посещаемость для конкретной тренировки
     */
    public function showAttendance(Training $training)
    {
        $user = Auth::user();

        // Проверяем права доступа
        if (!$user->isAdmin() && !$user->isCoach()) {
            abort(403, 'У вас нет прав для просмотра посещаемости');
        }

        // Если пользователь тренер, проверяем, что это его тренировка
        if ($user->isCoach() && $training->group->coach_id !== $user->id) {
            abort(403, 'Вы можете просматривать посещаемость только для своих тренировок');
        }

        // Получаем посещаемость с информацией о спортсменах
        $attendances = Attendance::where('training_id', $training->id)
            ->with('athlete')
            ->get();

        return view('attendance.show', compact('training', 'attendances'));
    }

    /**
     * Показать общую статистику посещаемости для группы
     */
    public function groupAttendance(Group $group)
    {
        $user = Auth::user();

        // Проверяем права доступа
        if (!$user->isAdmin() && !$user->isCoach()) {
            abort(403, 'У вас нет прав для просмотра посещаемости');
        }

        // Если пользователь тренер, проверяем, что это его группа
        if ($user->isCoach() && $group->coach_id !== $user->id) {
            abort(403, 'Вы можете просматривать посещаемость только для своих групп');
        }

        // Получаем тренировки группы с посещаемостью
        $trainings = Training::where('group_id', $group->id)
            ->where('status', 'completed')
            ->with(['attendances.athlete'])
            ->orderBy('start_time', 'desc')
            ->get();

        // Получаем всех спортсменов группы
        $athletes = $group->athletes;

        return view('attendance.group', compact('group', 'trainings', 'athletes'));
    }
}
