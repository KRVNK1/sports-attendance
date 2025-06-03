@extends('layouts.app')

@section('title', 'Спортсмены')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/athletes.css') }}">
@endsection

@section('content')
<div class="athletes-header">
    <h1>Спортсмены</h1>
    @if(auth()->user()->role == 'admin')
    <a href="{{ route('athletes.create') }}" class="btn btn-primary">Добавить спортсмена</a>
    @endif
</div>

<table class="table">
    <thead>
        <tr>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Телефон</th>
            <th>Дата рождения</th>
            <th>Группа</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($athletes as $athlete)
        <tr>
            <td>{{ $athlete->name }}</td>
            <td>{{ $athlete->surname }}</td>
            <td>{{ $athlete->phone }}</td>
            <td>{{ $athlete->birth }}</td>
            <td>
                @foreach ($athlete->groups as $group)
                <span class="badge badge-info">{{ $group->name }}</span><br>
                @endforeach
            </td>
            <td>
                <div class="btn-group">
                    @if(auth()->user()->role == 'admin')
                    <a href="{{ route('athletes.edit', $athlete->id) }}" class="btn btn-warning">Редактировать</a>
                    <form action="{{ route('athletes.destroy', $athlete->id) }}" method="POST" style="display: inline;">
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