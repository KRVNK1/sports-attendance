@extends('layouts.app')

@section('title', 'Группы')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/groups.css') }}">
@endsection

@section('content')
<div class="groups-header">
    <h1>Группы</h1>
    <a href="{{ route('groups.create') }}" class="btn btn-primary">Добавить группу</a>
</div>

<!-- <div class="schedule-week">
    <div class="day-header">Понедельник</div>
    <div class="day-content">
        <div class="day-item">
            <h3>Название</h3>
            <p>Время</p>
            <p>Тренер</p>
            <p>Зал</p>
        </div>
        <div class="day-item">
            <h3>Название</h3>
            <p>Время</p>
            <p>Тренер</p>
            <p>Зал</p>
        </div>
        <div class="day-item">
            <h3>Название</h3>
            <p>Время</p>
            <p>Тренер</p>
            <p>Зал</p>
        </div>
    </div>

    <div class="day-header">Среда</div>
    <div class="day-content">
        <div class="day-item">
            <h3>Название</h3>
            <p>Время</p>
            <p>Тренер</p>
            <p>Зал</p>
        </div>
    </div>

    <div class="day-header">Суббота</div>
    <div class="day-content">
        <div class="day-item">
            <h3>Название</h3>
            <p>Время</p>
            <p>Тренер</p>
            <p>Зал</p>
        </div>
        <div class="day-item">
            <h3>Название</h3>
            <p>Время</p>
            <p>Тренер</p>
            <p>Зал</p>
        </div>
    </div>
</div> -->

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
                    <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning">Редактировать</a>
                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection