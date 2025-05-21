<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Training;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $trainings = [];

        // Методы isAdmin, isCoach, isAthlete в модели User
        if ($user) {
            // Пользователь аутентифицирован
            if ($user->isAdmin()) {
                // Администратор видит все тренировки
                $trainings = Training::with(['group.coach'])
                    ->where('start_time', '>=', Carbon::now()->startOfDay())
                    ->orderBy('start_time')
                    ->get();
            } elseif ($user->isCoach()) {
                // Тренер видит только свои тренировки
                $trainings = Training::whereHas('group', function ($query) use ($user) {
                    $query->where('coach_id', $user->id);
                })
                    ->with(['group'])
                    ->where('start_time', '>=', Carbon::now()->startOfDay())
                    ->orderBy('start_time')
                    ->get();
            } elseif ($user->isAthlete()) {
                // Спортсмен видит тренировки своих групп
                $trainings = Training::whereHas('group.athletes', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                    ->with(['group.coach'])
                    ->where('start_time', '>=', Carbon::now()->startOfDay())
                    ->orderBy('start_time')
                    ->get();
            }
        } else {
            // Гость видит все активные тренировки (или можно настроить по-другому)
            $trainings = Training::with(['group.coach'])
                ->where('start_time', '>=', Carbon::now()->startOfDay())
                ->where('status', 'active')
                ->orderBy('start_time')
                ->get();
        }

        // Группируем тренировки по дням недели
        $trainingsByDay = $trainings->groupBy(function ($training) {
            return $training->dayOfWeek;
        });

        return view('schedule.index', compact('trainingsByDay'));
    }

    public function create()
    {
        $user = Auth::user();
        $groups = Group::all();
        $coaches = User::where('role', 'coach')->get();

        // Методы isAdmin и isCoach в модели User
        if ($user->isAdmin()) {
            $groups = Group::with('coach')->get();
        } elseif ($user->isCoach()) {
            $groups = Group::where('coach_id', $user->id)->get();
        }

        return view('schedule.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'recurring' => 'nullable|boolean',
            'weeks_count' => 'required_if:recurring,1|integer|min:1|max:12',
        ]);

        // Получаем текущую дату
        $currentDate = Carbon::now();

        // Преобразуем день недели из формы в день недели Carbon
        // В Carbon: 1 = понедельник, 7 = воскресенье
        $dayOfWeek = $request->day_of_week;

        // Находим следующую дату с указанным днем недели
        $trainingDate = $currentDate->copy();

        // Если текущий день недели совпадает с выбранным и время еще не прошло,
        // используем текущую дату, иначе ищем следующий подходящий день
        if (
            $trainingDate->dayOfWeek == $dayOfWeek &&
            $trainingDate->format('H:i') < $request->start_time
        ) {
            // Используем текущую дату
        } else {
            // Находим следующий день недели
            $daysToAdd = ($dayOfWeek - $trainingDate->dayOfWeek + 7) % 7;
            if ($daysToAdd == 0) {
                $daysToAdd = 7; // Если тот же день недели, переходим на следующую неделю
            }
            $trainingDate->addDays($daysToAdd);
        }

        // Разбиваем время на часы и минуты
        list($startHour, $startMinute) = explode(':', $request->start_time);
        list($endHour, $endMinute) = explode(':', $request->end_time);

        // Устанавливаем время начала и окончания
        $startTime = $trainingDate->copy()->setTime($startHour, $startMinute);
        $endTime = $trainingDate->copy()->setTime($endHour, $endMinute);

        // Проверяем, нет ли конфликтов с существующими тренировками
        $group = Group::findOrFail($request->group_id);
        $coach_id = $group->coach_id;

        // Проверка на конфликты для группы
        $groupConflict = Training::where('group_id', $request->group_id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        // Проверка на конфликты для тренера
        $coachConflict = Training::whereHas('group', function ($query) use ($coach_id) {
            $query->where('coach_id', $coach_id);
        })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($groupConflict) {
            return redirect()->back()->withInput()->with('error', 'Конфликт расписания: у этой группы уже есть тренировка в это время.');
        }

        if ($coachConflict) {
            return redirect()->back()->withInput()->with('error', 'Конфликт расписания: у этого тренера уже есть тренировка в это время.');
        }

        // Создаем первую тренировку
        Training::create([
            'group_id' => $request->group_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'active',
        ]);

        // Если тренировка повторяющаяся, создаем дополнительные записи
        if ($request->has('recurring') && $request->recurring && $request->weeks_count > 1) {
            for ($i = 1; $i < $request->weeks_count; $i++) {
                $nextStartTime = $startTime->copy()->addWeeks($i);
                $nextEndTime = $endTime->copy()->addWeeks($i);

                Training::create([
                    'group_id' => $request->group_id,
                    'start_time' => $nextStartTime,
                    'end_time' => $nextEndTime,
                    'location' => $request->location,
                    'notes' => $request->notes,
                    'status' => 'active',
                ]);
            }
        }

        return redirect()->route('schedule.index')->with('success', 'Тренировка успешно добавлена в расписание на ' . $startTime->format('d.m.Y (l)'));
    }

    public function edit(Training $training)
    {
        $groups = Group::all();
        $coaches = User::where('role', 'coach')->get();
        return view('schedule.edit', compact('training', 'groups', 'coaches'));
    }

    public function update(Request $request, Training $training)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:active,completed,cancelled',
        ]);

        // Получаем ID тренера из группы
        $group = Group::findOrFail($request->group_id);
        $coach_id = $group->coach_id;

        // Проверка на конфликты для группы (исключая текущую тренировку)
        $groupConflict = Training::where('group_id', $request->group_id)
            ->where('id', '!=', $training->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        // Проверка на конфликты для тренера (исключая текущую тренировку)
        $coachConflict = Training::whereHas('group', function ($query) use ($coach_id) {
            $query->where('coach_id', $coach_id);
        })
            ->where('id', '!=', $training->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($groupConflict) {
            return redirect()->back()->withInput()->with('error', 'Конфликт расписания: у этой группы уже есть тренировка в это время.');
        }

        if ($coachConflict) {
            return redirect()->back()->withInput()->with('error', 'Конфликт расписания: у этого тренера уже есть тренировка в это время.');
        }

        $training->update([
            'group_id' => $request->group_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()->route('schedule.index')->with('success', 'Тренировка успешно обновлена');
    }

    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('schedule.index')->with('success', 'Тренировка удалена');
    }
}
