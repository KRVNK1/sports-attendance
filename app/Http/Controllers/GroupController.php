<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    // Страница групп
    public function index()
    {
        $groups = Group::with('coach')->get();
        return view('groups.index', compact('groups'));
    }

    // Страница создания группы
    public function create()
    {
        $coaches = User::where('role', 'coach')->get(); // выбираем пользователей с ролью тренера
        return view('groups.create', compact('coaches'));
    }

    // Сохранение группы
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coach_id' => 'required|exists:users,id',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'coach_id' => $request->coach_id,
        ]);

        return redirect()->route('groups.index')->with('success', 'Группа успешно создана');
    }

    // Показ конкретной группы
    public function show(string $id)
    {
        $group = Group::with(['coach', 'athletes', 'trainings'])->findOrFail($id);
        return view('groups.show', compact('group'));
    }

    public function edit(string $id)
    {
        $group = Group::findOrFail($id); // поиск конкретной группы по id
        $coaches = User::where('role', 'coach')->get();
        return view('groups.edit', compact('group', 'coaches'));
    }

    // Обновление информации группы
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coach_id' => 'required|exists:users,id',
        ]);

        $group = Group::findOrFail($id);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'coach_id' => $request->coach_id,
        ]);

        return redirect()->route('groups.index')->with('success', 'Группа успешно обновлена');
    }

    // Удаление группы
    public function destroy(string $id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Группа успешно удалена');
    }
}
