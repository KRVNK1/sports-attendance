<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $trainings = Training::with(['group', 'coach', 'attendances.athlete'])
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($item) {
                return $item->start_time->format('Y-m-d');
            });

        return view('welcome', compact('trainings'));
    }

    public function create()
    {
        $groups = Group::all();
        $coaches = User::where('role', 'coach')->get();
        return view('schedule.create', compact('groups', 'coaches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'coach_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required',
            'notes' => 'nullable'
        ]);

        Training::create($request->all());

        return redirect()->route('schedule.index')->with('success', 'Тренировка добавлена');
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
            'coach_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required',
            'notes' => 'nullable'
        ]);

        $training->update($request->all());

        return redirect()->route('schedule.index')->with('success', 'Тренировка обновлена');
    }

    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('schedule.index')->with('success', 'Тренировка удалена');
    }
}
