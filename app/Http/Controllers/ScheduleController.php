<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    // Расписание тренировок(главная страница)
    public function index()
    {
        // Тренировки с тренером и сортировка по start_time
        $query = Training::with(['group.coach'])
            ->orderBy('start_time');

        // Проверяем роль пользователя и фильтруем тренировки
        $user = Auth::user();

        // Неавторизованные пользователи и спортсмены видят только предстоящие и активные тренировки
        if (!$user || $user->role === 'athlete') {
            $query->whereIn('status', ['upcoming', 'active']);
        }

        $trainings = $query->get();

        // Группируем тренировки по дням и возвращаем данные для отображения
        $trainingsByDay = $trainings->groupBy(function ($training) {
            return $training->start_time->format('Y-m-d');
        })->map(function ($dayTrainings, $date) {
            // Для каждой группы тренировок формируем структуру с датой, названием дня и списком тренировок
            $carbonDate = Carbon::parse($date); // Преобразуем строку даты в объект Carbon для работы с датой
            $dayName = $this->getDayName($carbonDate); // Получаем локализованное название дня недели с датой; getDayName - функция ниже
            return [
                'date' => $date,
                'day_name' => $dayName,
                'trainings' => $dayTrainings
            ];
        });

        return view('schedule.index', compact('trainingsByDay'));
    }

    // Получить название дня недели на русском
    private function getDayName($date)
    {
        $days = [
            'Monday' => 'Понедельник',
            'Tuesday' => 'Вторник',
            'Wednesday' => 'Среда',
            'Thursday' => 'Четверг',
            'Friday' => 'Пятница',
            'Saturday' => 'Суббота',
            'Sunday' => 'Воскресенье'
        ];

        return $days[$date->format('l')] . ', ' . $date->format('d.m.Y');
    }

    // Форма создания тренировки
    public function create()
    {
        $user = Auth::user();

        // Проверяем права пользователя
        if ($user->role === 'admin') {
            // Админ видит все группы
            $groups = Group::with('coach')->get();
        } elseif ($user->role === 'coach') {
            // Тренер видит только свои группы
            $groups = Group::where('coach_id', $user->id)->get();
        } else {
            // Спортсмены не могут создавать тренировки
            abort(403, 'У вас нет прав для создания тренировок');
        }

        return view('schedule.create', compact('groups'));
    }

    // Сохранить новую тренировку
    public function store(Request $request, Training $training)
    {
        // Проверяем данные формы (изменили валидацию для времени)
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        $group = Group::findOrFail($request->group_id);

        // Проверяем права доступа
        if ($user->role === 'coach' && $group->coach_id !== $user->id) {
            abort(403, 'Вы можете создавать тренировки только для своих групп');
        }

        // Создаем дату и время для тренировки
        $dayOfWeek = $request->day_of_week;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        // Находим ближайшую дату с нужным днем недели
        $today = Carbon::now();
        $targetDate = $today->copy();

        // Находим следующий день недели (1 = понедельник, 7 = воскресенье)
        // targetDate - сегодняшняя дата, dayOfWeek - свойство Carbon, возвращает номер дня недели текущей даты (1-7), 
        while ($targetDate->dayOfWeek != $dayOfWeek) {
            $targetDate->addDay(); // addDay() - метод Carbon, добавляет один день к дате
        }

        // Создаем полные даты и времена
        $fullStartTime = $targetDate->copy()->setTimeFromTimeString($startTime);
        $fullEndTime = $targetDate->copy()->setTimeFromTimeString($endTime);

        $conflicts = Training::where('group_id', $request->group_id)
            ->where(function ($query) use ($fullStartTime, $fullEndTime) {
                $query->where(function ($q) use ($fullStartTime, $fullEndTime) {
                    // Новая тренировка начинается во время существующей
                    $q->where('start_time', '<=', $fullStartTime)
                        ->where('end_time', '>', $fullEndTime);
                })->orWhere(function ($q) use ($fullEndTime) {
                    // Новая тренировка заканчивается во время существующей
                    $q->where('start_time', '<', $fullEndTime)
                        ->where('end_time', '>=', $fullEndTime);
                })->orWhere(function ($q) use ($fullStartTime, $fullEndTime) {
                    // Новая тренировка полностью перекрывает существующую
                    $q->where('start_time', '>=', $fullStartTime)
                        ->where('end_time', '<=', $fullEndTime);
                });
            })->exists();

        // Если есть конфликт
        if ($conflicts) {
            return redirect()->back()->with('error', 'У группы уже есть тренировка в это время');
        }

        // Создаем тренировку
        Training::create([
            'group_id' => $request->group_id,
            'start_time' => $fullStartTime,
            'end_time' => $fullEndTime,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'upcoming'
        ]);

        return redirect()->route('schedule.index')
            ->with('success', 'Тренировка успешно создана на ' . $fullStartTime->format('d.m.Y'));
    }


    // Форма редактирования тренировки
    public function edit(Training $training)
    {
        $user = Auth::user();

        // Проверяем права доступа
        if ($user->role === 'coach' && $training->group->coach_id !== $user->id) {
            abort(403, 'Вы можете редактировать только свои тренировки');
        }

        // Получаем группы в зависимости от роли
        if ($user->role === 'admin') {
            $groups = Group::with('coach')->get();
        } else {
            $groups = Group::where('coach_id', $user->id)->get();
        }

        return view('schedule.edit', compact('training', 'groups'));
    }

    // Обновление тренировки
    public function update(Request $request, Training $training)
    {
        // Проверяем данные формы (добавили status в валидацию)
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:upcoming,active,completed' // Добавили валидацию статуса
        ]);

        $user = Auth::user();

        // Проверяем права доступа
        if ($user->role === 'coach' && $training->group->coach_id !== $user->id) {
            abort(403, 'Вы можете редактировать только свои тренировки');
        }

        // Обновляем тренировку (добавили status в обновление)
        $training->update([
            'group_id' => $request->group_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => $request->status // Теперь статус тоже обновляется
        ]);

        return redirect()->route('schedule.index')
            ->with('success', 'Тренировка успешно обновлена');
    }

    // Удаление тренировки
    public function destroy(Training $training)
    {
        $user = Auth::user();

        // Проверяем права доступа
        if ($user->role === 'coach' && $training->group->coach_id !== $user->id) {
            abort(403, 'Вы можете удалять только свои тренировки');
        }

        $training->delete();

        return redirect()->route('schedule.index')
            ->with('success', 'Тренировка успешно удалена');
    }

    // Изменение статуса тренировки
    public function updateStatus(Training $training, Request $request)
    {
        // Проверяем, что статус правильный
        $request->validate([
            'status' => 'required|in:upcoming,active,completed'
        ]);

        $user = Auth::user();

        // Если пользователь тренер и его id тренировки не совпадает с id тренера
        if ($user->role === 'coach' && $training->group->coach_id !== $user->id) {
            return redirect()->back()->with('error', 'Вы можете изменять статус только своих тренировок');
        }

        // Смена статуса на тот, что пришел из формы
        $training->status = $request->status;
        $training->save();

        // Сообщения в зависимости от статуса
        $messages = [
            'upcoming' => 'Тренировка помечена как предстоящая',
            'active' => 'Тренировка помечена как активная',
            'completed' => 'Тренировка помечена как завершенная'
        ];

        return redirect()->back()
            ->with('success', $messages[$request->status]);
    }
}
