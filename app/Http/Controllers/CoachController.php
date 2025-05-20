<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = User::where('role', 'coach')->get();
        return view('coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('coaches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'birth' => 'required|date',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth' => $request->birth,
            'password' => bcrypt($request->password),
            'role' => 'coach'
        ]);

        return redirect()->route('coaches.index')->with('success', 'Тренер успешно добавлен');
    }

    public function edit(User $coach)
    {
        return view('coaches.edit', compact('coach'));
    }

    public function update(Request $request, User $coach)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users,email,' . $coach->id,
            'phone' => 'required',
            'birth' => 'required|date'
        ]);

        $coach->update($request->only(['name', 'surname', 'email', 'phone', 'birth']));

        return redirect()->route('coaches.index')->with('success', 'Данные тренера обновлены');
    }

    public function destroy(User $coach)
    {
        $coach->delete();
        return redirect()->route('coaches.index')->with('success', 'Тренер удален');
    }
}
