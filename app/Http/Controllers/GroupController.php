<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('athletes', 'trainings')->get();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $coaches = User::where('role', 'coach')->get();
        return view('groups.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'coach_id' => 'required|exists:users,id'
        ]);

        Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'coach_id' => $request->coach_id
        ]);

        return redirect()->route('groups.index')->with('success', 'Группа создана');
    }

    public function edit(Group $group)
    {
        $coaches = User::where('role', 'coach')->get();
        return view('groups.edit', compact('group', 'coaches'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'coach_id' => 'required|exists:users,id'
        ]);

        $group->update($request->only(['name', 'description', 'coach_id']));

        return redirect()->route('groups.index')->with('success', 'Группа обновлена');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Группа удалена');
    }
}
