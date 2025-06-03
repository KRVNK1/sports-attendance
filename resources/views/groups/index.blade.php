@extends('layouts.app')

@section('title', 'Группы')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/groups.css') }}">
@endsection

@section('content')
<div class="groups-header">
    <h1>Группы</h1>
    @if(auth()->user()->role == 'admin')
    <a href="{{ route('groups.create') }}" class="btn btn-primary">Добавить группу</a>
    @endif
</div>

<table class="table">
    <thead>
        <tr>
            <th>Название</th>
            <th>Тренер</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($groups as $group)
        <tr>
            <td>{{ $group->name }}</td>
            <td>{{ $group->coach->name ?? '' }} {{ $group->coach->surname ?? '' }}</td>
            <td>
                <div class="btn-group">
                    @if(auth()->user()->role == 'admin')
                    <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning">Редактировать</a>
                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                    @else
                    <span style="color:grey">Недоступно</span>
                    @endif
                    
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection