<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AthleteController extends Controller
{

    // Страница спортсмены
    public function index()
    {
        $athletes = User::where('role', 'athlete')->get();
        return view('athletes.index', compact('athletes'));
    }

    // Создание спортсмена
    public function create()
    {
        $groups = Group::all();
        return view('athletes.create', compact('groups'));
    }

    // Сохранение спортсмена
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'phone' => 'required|min:11|max:11',
            'birth' => 'required|date',
            'password' => 'required|min:8',
            'group_id' => 'nullable|exists:groups,id'
        ]);

        $athlete = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth' => $request->birth,
            'password' => $request->password,
            'role' => 'athlete'
        ]);

        // Если группа выбрана, то спортсмен присоеденияется(добавляется) к группе
        if ($request->group_id) {
            $athlete->groups()->attach($request->group_id);
        }

        return redirect()->route('athletes.index')->with('success', 'Спортсмен успешно добавлен');
    }

    public function edit(User $athlete)
    {
        $groups = Group::all();
        return view('athletes.edit', compact('athlete', 'groups'));
    }

    public function update(Request $request, User $athlete)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email,' . $athlete->id, //
            'phone' => 'required|min:11|max:11',
            'birth' => 'required|date',
            'group_id' => 'nullable|exists:groups,id'
        ]);

        // only - выборка определенных полей 
        $athlete->update($request->only(['name', 'surname', 'email', 'phone', 'birth']));

        // Если группа выбрана, то спортсмен присоеденияется(добавляется) к группе
        if ($request->group_id) {
            $athlete->groups()->sync($request->group_id);
        }

        return redirect()->route('athletes.index')->with('success', 'Данные спортсмена обновлены');
    }

    // Удаление пользователя
    public function destroy(User $athlete)
    {
        $athlete->delete();
        return redirect()->route('athletes.index')->with('success', 'Спортсмен удален');
    }

    // Профиль для спорстмена
    public function profile()
    {
        // Получаем текущего аутентифицированного пользователя
        $athlete = Auth::user();
        
        // Загружаем связанные данные (группы и посещаемость)
        $athlete->load(['groups', 'attendances.training.group']);
        
        return view('athletes.profile', compact('athlete'));
    }
}
