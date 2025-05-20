<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    public function index()
    {
        $athletes = User::where('role', 'athlete')->get();
        return view('athletes.index', compact('athletes'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('athletes.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'birth' => 'required|date',
            'password' => 'required|min:6',
            'group_id' => 'nullable|exists:groups,id'
        ]);

        $athlete = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth' => $request->birth,
            'password' => bcrypt($request->password),
            'role' => 'athlete'
        ]);

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
            'email' => 'required|email|unique:users,email,' . $athlete->id,
            'phone' => 'required',
            'birth' => 'required|date',
            'group_id' => 'nullable|exists:groups,id'
        ]);

        $athlete->update($request->only(['name', 'surname', 'email', 'phone', 'birth']));

        if ($request->group_id) {
            $athlete->groups()->sync($request->group_id);
        }

        return redirect()->route('athletes.index')->with('success', 'Данные спортсмена обновлены');
    }

    public function destroy(User $athlete)
    {
        $athlete->delete();
        return redirect()->route('athletes.index')->with('success', 'Спортсмен удален');
    }
}
